<?php
namespace WoohooLabs\Worm\Query;

use Closure;

interface SelectQueryBuilderInterface
{
    /**
     * @param array $fields
     * @return $this
     */
    public function select(array $fields);

    /**
     * @param string $table
     * @return $this
     */
    public function from($table);

    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function where($operand1, $operator, $operand2, $connector = "and");

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function whereRaw($condition, array $params = [], $connector = "and");

    /**
     * @param Closure $condition
     * @param string $connector
     * @return $this
     */
    public function whereNested(Closure $condition, $connector = "and");

    /**
     * @param string $table
     * @param Closure $condition
     * @param string $type
     * @return $this
     */
    public function join($table, Closure $condition, $type = "INNER");

    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function having($operand1, $operator, $operand2, $connector = "and");

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function havingRaw($condition, array $params = [], $connector = "and");

    /**
     * @param Closure $condition
     * @param string $connector
     * @return $this
     */
    public function havingNested(Closure $condition, $connector = "and");

    /**
     * @param string $attribute
     * @return $this
     */
    public function groupBy($attribute);

    /**
     * @param array $attributes
     * @return $this
     */
    public function groupByAttributes(array $attributes);

    /**
     * @param string $attribute
     * @param string $direction
     * @return $this
     */
    public function orderBy($attribute, $direction = "ASC");

    /**
     * @param int|null $limit
     * @return $this
     */
    public function limit($limit);

    /**
     * @param int|null $offset
     * @return $this
     */
    public function offset($offset);

    /**
     * @return mixed
     */
    public function execute();
}
