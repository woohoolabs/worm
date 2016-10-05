<?php
namespace WoohooLabs\Worm;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\SelectQueryBuilder;

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

    /**
     * @return \WoohooLabs\Worm\Query\SelectQueryBuilderInterface
     */
    public function query()
    {
        return new SelectQueryBuilder($this->connection);
    }

    /**
     * @param \WoohooLabs\Worm\Model\ModelInterface $model
     * @return \WoohooLabs\Worm\Query\SelectQueryBuilderInterface
     */
    public function queryModel(ModelInterface $model)
    {
        $queryBuilder = new SelectQueryBuilder($this->connection);
        $queryBuilder->from($model->getTable());

        return $queryBuilder;
    }
}
