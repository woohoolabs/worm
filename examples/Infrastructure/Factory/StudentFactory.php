<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Factory;

use WoohooLabs\Worm\Examples\Domain\Name;
use WoohooLabs\Worm\Examples\Domain\Student;
use WoohooLabs\Worm\Examples\Infrastructure\Model\StudentModel;
use WoohooLabs\Worm\Execution\IdentityMap;

class StudentFactory extends AbstractFactory
{
    private StudentModel $model;

    public function __construct(IdentityMap $identityMap, StudentModel $model)
    {
        parent::__construct($identityMap);
        $this->model = $model;
    }

    /**
     * @return Student[]
     */
    public function createStudents(array $entities): array
    {
        return array_map(
            function (array $entity) {
                return $this->createStudent($entity);
            },
            $entities
        );
    }

    public function createStudent(array $entity): Student
    {
        $factory = function () use ($entity): Student {
            return new Student(
                $entity[$this->model->id],
                new Name(
                    $entity[$this->model->first_name],
                    $entity[$this->model->last_name]
                ),
                $entity[$this->model->introduction],
                $entity[$this->model->birthday] ?? null,
                $entity[$this->model->gender] ?? null
            );
        };

        return $this->createObject($this->model, $entity, $factory);
    }
}
