<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Worm\Connection\ConnectionInterface;

interface QueryInterface
{
    public function getConnection(): ConnectionInterface;
}
