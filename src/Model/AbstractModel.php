<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryBuilder;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @param string[] $relationships
     */
    public function query(ConnectionInterface $connection, array $relationships = []): SelectQueryBuilder
    {
        $queryBuilder = new SelectQueryBuilder($connection);
        $queryBuilder
            ->from($this->getTable());

        return $queryBuilder;
    }
}
