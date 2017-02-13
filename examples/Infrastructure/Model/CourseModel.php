<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class CourseModel extends AbstractModel
{
    public $id;
    public $name;
    public $description;
    public $credit;
    public $language;
    public $classes;

    /**
     * @var ClassModel
     */
    private $classModel;

    public function __construct(ClassModel $classModel)
    {
        $this->classModel = $classModel;
        parent::__construct();
    }

    public function getTable(): string
    {
        return "courses";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    protected function getRelationships(): array
    {
        return [
            "classes" => $this->hasMany(
                $this->classModel,
                $this->classModel->course_id,
                $this->id
            )
        ];
    }
}
