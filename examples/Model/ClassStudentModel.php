<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class ClassStudentModel extends AbstractModel
{
    public function getTable(): string
    {
        return "classes_students";
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
