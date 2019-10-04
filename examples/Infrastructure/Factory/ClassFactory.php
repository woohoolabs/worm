<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Factory;

use DateTimeImmutable;
use WoohooLabs\Worm\Examples\Domain\SchoolClass;
use WoohooLabs\Worm\Examples\Infrastructure\Model\ClassModel;
use WoohooLabs\Worm\Execution\IdentityMap;

class ClassFactory extends AbstractFactory
{
    private ClassModel $model;
    private StudentFactory $studentFactory;

    public function __construct(IdentityMap $identityMap, ClassModel $model, StudentFactory $studentFactory)
    {
        parent::__construct($identityMap);
        $this->model = $model;
        $this->studentFactory = $studentFactory;
    }

    /**
     * @return SchoolClass[]
     */
    public function createClasses(array $entities): array
    {
        return array_map(
            function (array $entity) {
                return $this->createClass($entity);
            },
            $entities
        );
    }

    public function createClass(array $entity): SchoolClass
    {
        $factory = function () use ($entity): SchoolClass {
            return new SchoolClass(
                (int) $entity[$this->model->id],
                (int) $entity[$this->model->room_id],
                (int) $entity[$this->model->teacher_id],
                $this->studentFactory->createStudents($entity[$this->model->students] ?? []),
                new DateTimeImmutable($entity[$this->model->datetime])
            );
        };

        return $this->createObject($this->model, $entity, $factory);
    }
}
