<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class ClassStudentModel extends AbstractModel
{
    /** @var string */
    public $id;
    /** @var string */
    public $class_id;
    /** @var string */
    public $student_id;

    public function getTable(): string
    {
        return "classes_students";
    }

    public function getPrimaryKeys(): array
    {
        return [$this->id];
    }

    protected function getRelationships(): array
    {
        return [];
    }
}
