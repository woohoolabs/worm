<?php
namespace WoohooLabs\Worm\Query;

use Closure;
use WoohooLabs\Worm\Connection\ConnectionInterface;

class SelectQueryBuilder implements SelectQueryBuilderInterface, SelectQueryInterface
{
    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @var array
     */
    protected $select = [];

    /**
     * @var string
     */
    protected $from = "";

    /**
     * @var array
     */
    protected $aggregate = [];

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var ConditionBuilder
     */
    protected $where;

    /**
     * @var array
     */
    protected $groupBy = [];

    /**
     * @var ConditionBuilder
     */
    protected $having;

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
     * @var array
     */
    protected $params = [];

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->where = new ConditionBuilder();
        $this->having = new ConditionBuilder;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
        $this->select = $fields;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function from($table)
    {
        $this->from = $table;

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
        $this->where->add($operand1, $operator, $operand2, $connector);

        return $this;
    }

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function whereRaw($condition, array $params = [], $connector = "and")
    {
        $this->where->addRaw($condition, $params, $connector);

        return $this;
    }

    /**
     * @param Closure $condition
     * @param string $connector
     * @return $this
     */
    public function whereNested(Closure $condition, $connector = "and")
    {
        $this->where->addNested($condition, $connector);

        return $this;
    }

    /**
     * @param string $table
     * @param Closure $condition
     * @return $this
     */
    public function leftJoin($table, Closure $condition)
    {
        return $this->join($table, $condition, "LEFT");
    }

    /**
     * @param string $table
     * @param Closure $condition
     * @return $this
     */
    public function rightJoin($table, Closure $condition)
    {
        return $this->join($table, $condition, "RIGHT");
    }

    /**
     * @param string $table
     * @param Closure $condition
     * @param string $type
     * @return $this
     */
    public function join($table, Closure $condition, $type = "INNER")
    {
        $this->join[] = [
            "type" => $type,
            "table" => $table,
            "on" => $condition(new ConditionBuilder()),
        ];

        return $this;
    }

    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function having($operand1, $operator, $operand2, $connector = "and")
    {
        $this->having->add($operand1, $operator, $operand2, $connector);

        return $this;
    }

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function havingRaw($condition, array $params = [], $connector = "and")
    {
        $this->having->addRaw($condition, $params, $connector);

        return $this;
    }

    /**
     * @param Closure $condition
     * @param string $connector
     * @return $this
     */
    public function havingNested(Closure $condition, $connector = "and")
    {
        $this->having->addNested($condition, $connector);

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

    /**
     * @return mixed
     */
    public function execute()
    {
        $sql = $this->connection->getTranslator()->translateSelectQuery($this);
        return $this->connection->queryAll($sql);
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getAggregate()
    {
        return $this->aggregate;
    }

    /**
     * @return array
     */
    public function getJoin()
    {
        return $this->join;
    }

    /**
     * @return ConditionBuilder
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return array
     */
    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     * @return ConditionBuilder
     */
    public function getHaving()
    {
        return $this->having;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return array
     */
    public function getUnion()
    {
        return $this->union;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
