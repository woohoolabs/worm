<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\Insert\InsertQueryInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

interface DriverInterface
{
    public function translateSelectQuery(SelectQueryInterface $query): string;

    public function translateInsertQuery(InsertQueryInterface $query): string;
}
