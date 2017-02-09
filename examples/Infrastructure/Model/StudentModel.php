<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

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

    public function isAutoIncremented(): bool
    {
        return true;
    }

    protected function getRelationships(): array
    {
        return [
            "classes" => function () {
                return $this->hasManyThrough(
                    $this->id,
                    $this->classStudentModel,
                    $this->classStudentModel->student_id,
                    $this->classStudentModel->class_id,
                    $this->classModel,
                    "id"
                );
            }
        ];
    }
}
