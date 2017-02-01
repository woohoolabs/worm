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

    /**
     * @var ClassStudentModel
     */
    private $classStudentModel;

    /**
     * @var ClassModel
     */
    private $classModel;

    public function __construct(ClassStudentModel $classStudentModel, ClassModel $classModel)
    {
        $this->classStudentModel = $classStudentModel;
        $this->classModel = $classModel;
        parent::__construct();
    }

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
            "classes" => function () {
                return new HasManyThroughRelationship(
                    $this->classStudentModel,
                    "student_id",
                    "class_id",
                    $this->classModel,
                    $this->id
                );
            }
        ];
    }

    public function getClassStudentModel(): ClassStudentModel
    {
        return $this->classStudentModel;
    }

    public function getClassModel(): ClassModel
    {
        return $this->classModel;
    }
}
