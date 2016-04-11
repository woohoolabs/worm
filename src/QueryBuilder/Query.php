<?php
namespace WoohooLabs\Worm\QueryBuilder;

use Closure;
use WoohooLabs\Worm\Connection\ConnectionInterface;

class Query
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $aggregate = [];

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
    protected $groupBy = [];

    /**
     * @var array
     */
    protected $having = [];

    /**
     * @var array
     */
    protected $orderBy = [];

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var array
     */
    protected $union = [];

    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface|null
     */
    protected $connection;

    public function __construct(ConnectionInterface $connection = null)
    {
        $this->connection = $connection;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function from($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function where($operand1, $operator, $operand2, $connector = "and")
    {
        $this->where[] = [
            "operand1" => $operand1,
            "operator" => $operator,
            "operand2" => $operand2,
            "connector" => $connector
        ];

        return $this;
    }

    public function nestedWhere(Closure $nestedWhere, $connector = "and")
    {
        $query = new Query($this->connection);
        $nestedWhere($query);

        $this->where[] = [
            "nested" => $query->where,
            "connector" => $connector
        ];

        return $this;
    }

    public function join()
    {
        $this->join[] = [
            "table" => "",
            "field" => "",
        ];

        return $this;
    }

    /**
     * @param string $attribute
     * @return $this
     */
    public function groupBy($attribute)
    {
        $this->groupBy[$attribute];

        return $this;
    }

    public function groupByAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->groupBy($attribute);
        }

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $direction
     * @return $this
     */
    public function orderBy($attribute, $direction = "ASC")
    {
        $this->orderBy[] = ["attribute" => $attribute, "direction" => $direction];

        return $this;
    }

    /**
     * @param int|null $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int|null $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function count()
    {
    }

    public function find()
    {
    }

    public function getList()
    {
    }
}
