<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Model;

use WoohooLabs\Worm\Model\AbstractModel;

class StudentModel extends AbstractModel
{
    /** @var string */
    public $id;
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $birthday;
    /** @var string */
    public $gender;
    /** @var string */
    public $introduction;

    public function getTable(): string
    {
        return "students";
    }

    public function getPrimaryKeys(): array
    {
        return [$this->id];
    }

    protected function getRelationships(): array
    {
        return [];
    }
}
