<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Examples\Domain\Course;
use WoohooLabs\Worm\Examples\Domain\SchoolClass;
use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\Model\Relationship\AbstractRelationship;

class ClassModel extends AbstractModel
{
    public $id;
    public $course_id;
    public $room_id;
    public $teacher_id;
    public $students;
    public $datetime;

    private ClassStudentModel $classStudentModel;
    private StudentModel $studentModel;

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

    public function getPrimaryKeys(): array
    {
        return [$this->id];
    }

    public function mapClass(Course $course, SchoolClass $class): array
    {
        return [
            $this->id => $class->getId(),
            $this->course_id => $course->getId(),
            $this->room_id => $class->getRoomId(),
            $this->teacher_id => $class->getTeacherId(),
            $this->datetime => $class->getDatetime()->format("Y-m-d H:i:s"),
        ];
    }

    protected function getRelationships(): array
    {
        return [
            "students" => function (): AbstractRelationship {
                return $this->hasManyThrough(
                    $this->id,
                    $this->classStudentModel,
                    $this->classStudentModel->class_id,
                    $this->classStudentModel->student_id,
                    $this->studentModel,
                    $this->studentModel->id
                );
            },
        ];
    }
}
