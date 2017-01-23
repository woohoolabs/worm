<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Select;

use Closure;

interface SelectQueryBuilderInterface
{
    public function select(array $fields): SelectQueryBuilderInterface;

    public function distinct(bool $isDistinct = true): SelectQueryBuilderInterface;

    public function from(string $table, string $alias = ""): SelectQueryBuilderInterface;

    public function fromSubquery(Closure $subquery, string $alias): SelectQueryBuilderInterface;

    public function where(Closure $condition): SelectQueryBuilderInterface;

    public function leftJoin(string $table, string $alias = ""): SelectQueryBuilderInterface;

    public function rightJoin(string $table, string $alias = ""): SelectQueryBuilderInterface;

    public function join(string $table, string $alias = "", string $type = ""): SelectQueryBuilderInterface;

    public function on(Closure $condition): SelectQueryBuilderInterface;

    public function having(string $operand1, string $operator, string $operand2, string $connector = "and"): SelectQueryBuilderInterface;

    public function havingRaw(string $condition, array $params = [], string $connector = "and"): SelectQueryBuilderInterface;

    public function havingNested(Closure $condition, string $connector = "and"): SelectQueryBuilderInterface;

    public function groupBy(string $attribute): SelectQueryBuilderInterface;

    public function groupByAttributes(array $attributes): SelectQueryBuilderInterface;

    public function orderBy(string $attribute, string $direction = "ASC"): SelectQueryBuilderInterface;

    /**
     * @param int|null $limit
     */
    public function limit($limit): SelectQueryBuilderInterface;

    /**
     * @param int|null $offset
     */
    public function offset($offset): SelectQueryBuilderInterface;

    public function execute(): array;

    public function getSql(): string;

    public function getParams(): array;
}
