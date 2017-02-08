<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\Model\Relationship\BelongsToOneRelationship;

class ClassModel extends AbstractModel
{
    public $id;
    public $course_id;

    /**
     * @var CourseModel
     */
    private $courseModel;

    public function __construct(CourseModel $courseModel)
    {
        $this->courseModel = $courseModel;
        parent::__construct();
    }

    public function getTable(): string
    {
        return "classes";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    public function isAutoIncremented(): bool
    {
        return true;
    }

    public function getRelationships(): array
    {
        return [
            "courses" => function () {
                return $this->belongsToOne($this->courseModel, $this->course_id, $this->courseModel->id);
            }
        ];
    }

    public function getCourseModel(): CourseModel
    {
        return $this->courseModel;
    }
}
