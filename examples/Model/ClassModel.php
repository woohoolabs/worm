<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\Model\Relationship\BelongsToOneRelationship;

class ClassModel extends AbstractModel
{
    public $id;
    public $course_id;

    public function getTable(): string
    {
        return "classes";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    public function getRelationships(): array
    {
        return [
            "courses" => function () {
                return new BelongsToOneRelationship(CourseModel::class, $this->course_id, "id");
            }
        ];
    }
}
