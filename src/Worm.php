<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\SelectQueryBuilder;
use WoohooLabs\Worm\Query\SelectQueryBuilderInterface;

class Worm
{
    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface
     */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function query(): SelectQueryBuilderInterface
    {
        return new SelectQueryBuilder($this->connection);
    }

    public function queryModel(ModelInterface $model): SelectQueryBuilderInterface
    {
        $queryBuilder = new SelectQueryBuilder($this->connection);
        $queryBuilder->from($model->getTable());

        return $queryBuilder;
    }
}
