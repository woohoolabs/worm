<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class ClassModel extends AbstractModel
{
    public $id;
    public $course_id;
    public $room_id;
    public $teacher_id;
    public $students;
    public $datetime;

    /**
     * @var ClassStudentModel
     */
    private $classStudentModel;

    /**
     * @var StudentModel
     */
    private $studentModel;

    public function __construct(ClassStudentModel $classStudentModel, StudentModel $studentModel)
    {
        parent::__construct();
        $this->classStudentModel = $classStudentModel;
        $this->studentModel = $studentModel;
    }

    public function getTable(): string
    {
        return "classes";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    protected function getRelationships(): array
    {
        return [
            "students" => function () {
                return $this->hasManyThrough(
                    $this->id,
                    $this->classStudentModel,
                    $this->classStudentModel->class_id,
                    $this->classStudentModel->student_id,
                    $this->studentModel,
                    $this->studentModel->id
                );
            }
        ];
    }
}
