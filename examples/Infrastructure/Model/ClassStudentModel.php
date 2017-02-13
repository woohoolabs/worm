<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class ClassStudentModel extends AbstractModel
{
    public $id;
    public $class_id;
    public $student_id;

    public function getTable(): string
    {
        return "classes_students";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    protected function getRelationships(): array
    {
        return [];
    }
}
