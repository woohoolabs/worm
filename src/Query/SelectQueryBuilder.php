<?php
declare(strict_types=1);

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

    public function select(array $fields): SelectQueryBuilderInterface
    {
        $this->select = $fields;

        return $this;
    }

    public function from(string $table): SelectQueryBuilderInterface
    {
        $this->from = $table;

        return $this;
    }

    public function where(string $operand1, string $operator, string $operand2, string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->where->add($operand1, $operator, $operand2, $connector);

        return $this;
    }

    public function whereRaw(string $condition, array $params = [], string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->where->addRaw($condition, $params, $connector);

        return $this;
    }

    public function whereNested(Closure $condition, string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->where->addNested($condition, $connector);

        return $this;
    }

    public function leftJoin(string $table, Closure $condition): SelectQueryBuilderInterface
    {
        return $this->join($table, $condition, "LEFT");
    }

    public function rightJoin(string $table, Closure $condition): SelectQueryBuilderInterface
    {
        return $this->join($table, $condition, "RIGHT");
    }

    public function join(string $table, Closure $condition, string $type = "INNER"): SelectQueryBuilderInterface
    {
        $this->join[] = [
            "type" => $type,
            "table" => $table,
            "on" => $condition(new ConditionBuilder()),
        ];

        return $this;
    }

    public function having(string $operand1, string $operator, string $operand2, string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->having->add($operand1, $operator, $operand2, $connector);

        return $this;
    }

    public function havingRaw(string $condition, array $params = [], string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->having->addRaw($condition, $params, $connector);

        return $this;
    }

    public function havingNested(Closure $condition, string $connector = "and"): SelectQueryBuilderInterface
    {
        $this->having->addNested($condition, $connector);

        return $this;
    }

    public function groupBy(string $attribute): SelectQueryBuilderInterface
    {
        $this->groupBy[] = $attribute;

        return $this;
    }

    public function groupByAttributes(array $attributes): SelectQueryBuilderInterface
    {
        foreach ($attributes as $attribute) {
            $this->groupBy($attribute);
        }

        return $this;
    }

    public function orderBy(string $attribute, string $direction = "ASC"): SelectQueryBuilderInterface
    {
        $this->orderBy[] = ["attribute" => $attribute, "direction" => $direction];

        return $this;
    }

    public function limit($limit): SelectQueryBuilderInterface
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset($offset): SelectQueryBuilderInterface
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

    public function getSelect(): array
    {
        return $this->select;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getAggregate(): array
    {
        return $this->aggregate;
    }

    public function getJoin(): array
    {
        return $this->join;
    }

    public function getWhere(): ConditionBuilder
    {
        return $this->where;
    }

    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    public function getHaving(): ConditionBuilder
    {
        return $this->having;
    }

    public function getOrderBy(): array
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

    public function getUnion(): array
    {
        return $this->union;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
