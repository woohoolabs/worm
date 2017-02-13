<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Worm\Execution\Executor;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\SelectQueryBuilder;

class Worm
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var Executor
     */
    private $executor;

    public function __construct(ConnectionInterface $connection, IdentityMap $identityMap)
    {
        $this->connection = $connection;
        $this->executor = new Executor($connection, $identityMap);
    }

    public function queryModel(ModelInterface $model): SelectQueryBuilder
    {
        $queryBuilder = new SelectQueryBuilder($model, $this->connection, $this->executor);

        return $queryBuilder;
    }

    public function getIdentityMap(): IdentityMap
    {
        return $this->executor->getIdentityMap();
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
