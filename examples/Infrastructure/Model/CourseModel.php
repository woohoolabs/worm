<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Examples\Domain\Course;
use WoohooLabs\Worm\Model\AbstractModel;

class CourseModel extends AbstractModel
{
    public $id;
    public $name;
    public $description;
    public $credit;
    public $language;
    public $classes;
    public ClassModel $classModel;

    public function __construct(ClassModel $classModel)
    {
        $this->classModel = $classModel;
        parent::__construct();
    }

    public function getTable(): string
    {
        return "courses";
    }

    public function getPrimaryKeys(): array
    {
        return [$this->id];
    }

    public function getClassModel(): ClassModel
    {
        return $this->classModel;
    }

    public function mapCourse(Course $course): array
    {
        return [
            $this->id => $course->getId(),
            $this->name => $course->getName(),
            $this->description => $course->getDescription(),
            $this->credit => $course->getCredit(),
        ];
    }

    protected function getRelationships(): array
    {
        return [
            "classes" => $this->hasMany(
                $this->classModel,
                $this->classModel->course_id,
                $this->id,
                true
            )
        ];
    }
}
