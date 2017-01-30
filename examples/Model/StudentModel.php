<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\Model\Relationship\HasManyThroughRelationship;

class StudentModel extends AbstractModel
{
    public $id;
    public $first_name;
    public $last_name;
    public $birthday;
    public $gender;
    public $introduction;

    public function getTable(): string
    {
        return "students";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    public function getRelationships(): array
    {
        return [
            "classes" => function() {
                return new HasManyThroughRelationship(
                    ClassStudentModel::class,
                    "student_id",
                    "class_id",
                    ClassModel::class,
                    $this->id
                );
            }
        ];
    }
}
