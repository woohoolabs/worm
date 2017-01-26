<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;

class Worm
{
    /**
     * @var \WoohooLabs\Larva\Connection\ConnectionInterface
     */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function queryModel(ModelInterface $model): SelectQueryBuilderInterface
    {
        $queryBuilder = new SelectQueryBuilder($this->connection);
        $queryBuilder->from($model->getTable());

        return $queryBuilder;
    }

    public function getLog(): array
    {
        return $this->connection->getLog();
    }
}
