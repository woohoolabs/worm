<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

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

    public function isAutoIncremented(): bool
    {
        return true;
    }

    protected function getRelationships(): array
    {
        return [];
    }
}
