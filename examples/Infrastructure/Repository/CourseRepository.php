<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Repository;

use WoohooLabs\Worm\Examples\Domain\Course;
use WoohooLabs\Worm\Examples\Infrastructure\Factory\CourseFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Query\ConditionBuilder;
use WoohooLabs\Worm\Worm;

class CourseRepository extends AbstractRepository
{
    /**
     * @var CourseModel
     */
    private $model;

    /**
     * @var CourseFactory
     */
    private $courseFactory;

    public function __construct(Worm $worm, CourseModel $model, CourseFactory $factory)
    {
        parent::__construct($worm);
        $this->model = $model;
        $this->courseFactory = $factory;
    }

    /**
     * @return Course[]
     */
    public function getCoursesInRoom($roomNumber): array
    {
        $courses = $this->worm
            ->query($this->model)
            ->withAllTransitiveRelationships()
            ->where(
                ConditionBuilder::create($this->model)
                    ->has(
                        $this->model->classes,
                        ConditionBuilder::create()
                            ->columnToValue($this->model->classModel->room_id, "=", $roomNumber)
                    )
            )
            ->fetchAll();

        return $this->courseFactory->createCourses($courses);
    }

    /**
     * @return void
     */
    public function save(Course $course)
    {
        $record = $this->model->mapCourse($course);

        $classRecords = [];
        foreach ($course->getClasses() as $class) {
            $classRecords[] = $this->model->getClassModel()->mapClass($course, $class);
        }

        $this->worm->beginTransaction();

        $this->worm->save($this->model, $record, $course);
        $this->worm->saveRelatedEntities(
            $this->model,
            $this->model->classes,
            $record,
            $classRecords,
            $course->getClasses()
        );

        $this->worm->rollback();
    }
}
