<?php
namespace WoohooLabs\Worm\QueryBuilder;

use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Model\AbstractModel;

class Query
{
    /**
     * @var array
     */
    protected $select = [];

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var array
     */
    protected $having = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var array
     */
    protected $union = [];

    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface|null
     */
    protected $connection;

    /**
     * @var \WoohooLabs\Worm\Model\AbstractModel
     */
    protected $model;

    public function __construct(AbstractModel $model = null, ConnectionInterface $connection = null)
    {
        $this->connection = $connection;
        $this->model = $model;
    }

    public function where($attribute1, $operator, $attribute2, $connector = "and")
    {
        $this->where[] = ["attribute"];
        return $this;
    }

    public function find()
    {
    }

    public function getList()
    {
    }
}
