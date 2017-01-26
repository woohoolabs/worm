<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\Model\Relationship\ManyToManyRelationship;

class StudentModel extends AbstractModel
{
    public function getTable(): string
    {
        return "students";
    }

    public function getPrimaryKey(): string
    {
        return "id";
    }

    public function getRelationships(): array
    {
        return [
            "classes" => function() {
                return new ManyToManyRelationship(
                    ClassStudentModel::class,
                    "student_id",
                    "class_id",
                    ClassModel::class,
                    "id"
                );
            }
        ];
    }
}
