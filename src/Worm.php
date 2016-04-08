<?php
namespace WoohooLabs\Worm;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Model\AbstractModel;
use WoohooLabs\Worm\QueryBuilder\Query;

class Worm
{
    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface
     */
    protected $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param \WoohooLabs\Worm\Model\AbstractModel $model
     * @return \WoohooLabs\Worm\QueryBuilder\Query
     */
    public function query(AbstractModel $model)
    {
        return new Query($model, $this->connection);
    }
}
