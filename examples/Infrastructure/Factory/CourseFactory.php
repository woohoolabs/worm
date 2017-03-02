<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Factory;

use WoohooLabs\Worm\Examples\Domain\Course;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Execution\IdentityMap;

class CourseFactory extends AbstractFactory
{
    /**
     * @var CourseModel
     */
    private $model;

    /**
     * @var ClassFactory
     */
    private $classFactory;

    public function __construct(IdentityMap $identityMap, CourseModel $model, ClassFactory $classFactory)
    {
        parent::__construct($identityMap);
        $this->model = $model;
        $this->classFactory = $classFactory;
    }

    /**
     * @return Course[]
     */
    public function createCourses(array $entities): array
    {
        return array_map(
            function (array $entity) {
                return $this->createCourse($entity);
            },
            $entities
        );
    }

    public function createCourse(array $entity): Course
    {
        $factory = function () use ($entity): Course {
            return new Course(
                (int) $entity[$this->model->id],
                $entity[$this->model->name],
                $entity[$this->model->description],
                (int) $entity[$this->model->credit],
                $entity[$this->model->language],
                $this->classFactory->createClasses($entity[$this->model->classes] ?? [])
            );
        };

        return $this->createObject($this->model, $entity, $factory);
    }
}
