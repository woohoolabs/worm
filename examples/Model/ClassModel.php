<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class ClassModel extends AbstractModel
{
    public function getTable(): string
    {
        return "classes";
    }

    public function getPrimaryKey(): string
    {
        return "id";
    }

    public function getRelationships(): array
    {
        return [
        ];
    }
}
