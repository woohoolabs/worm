<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class CourseModel extends AbstractModel
{
    public $id;

    public function getTable(): string
    {
        return "courses";
    }

    public function getPrimaryKey(): string
    {
        return $this->id;
    }

    public function getRelationships(): array
    {
        return [];
    }
}
