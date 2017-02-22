<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Repository;

use WoohooLabs\Worm\Examples\Domain\Course;
use WoohooLabs\Worm\Examples\Infrastructure\Factory\CourseFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Worm;

class CourseRepository extends AbstractRepository
{
    /**
     * @var CourseModel
     */
    private $courseModel;

    /**
     * @var CourseFactory
     */
    private $courseFactory;

    public function __construct(Worm $worm, CourseModel $courseModel, CourseFactory $courseFactory)
    {
        parent::__construct($worm);
        $this->courseModel = $courseModel;
        $this->courseFactory = $courseFactory;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        $courses = $this->worm
            ->queryModel($this->courseModel)
            ->withRelationships($this->courseModel->getAllRelationshipNames())
            ->fetchAll();

        return $this->courseFactory->createCourses($courses);
    }

    /**
     * @return void
     */
    public function save(Course $course)
    {
        $entity = [];

        $this->worm->save($this->courseModel, $entity, $course);
    }
}
