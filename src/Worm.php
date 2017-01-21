<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryBuilder;
use WoohooLabs\Worm\Query\Select\SelectQueryBuilderInterface;

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

    public function select(): SelectQueryBuilderInterface
    {
        return new SelectQueryBuilder($this->connection);
    }

    public function selectModel(ModelInterface $model): SelectQueryBuilderInterface
    {
        $queryBuilder = new SelectQueryBuilder($this->connection);
        $queryBuilder->from($model->getTable());

        return $queryBuilder;
    }
}
