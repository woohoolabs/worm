<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use Closure;
use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder as LarvaSelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\Executor;
use WoohooLabs\Worm\Model\ModelInterface;

class SelectQueryBuilder
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var Executor
     */
    private $executor;

    /**
     * @var LarvaSelectQueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $relationships = [];

    public function __construct(ModelInterface $model, ConnectionInterface $connection, Executor $executor)
    {
        $this->model = $model;
        $this->executor = $executor;
        $this->queryBuilder = new LarvaSelectQueryBuilder($connection);
        $this->queryBuilder->from($model->getTable());
    }

    public function select(array $select): SelectQueryBuilder
    {
        $this->queryBuilder->select($select);

        return $this;
    }

    public function selectExpression(string $expression, string $alias = ""): SelectQueryBuilder
    {
        $this->queryBuilder->selectExpression($expression, $alias);

        return $this;
    }

    public function selectColumn(string $column, string $prefix = "", string $alias = ""): SelectQueryBuilder
    {
        $this->queryBuilder->selectColumn($column, $prefix, $alias);

        return $this;
    }

    public function distinct(bool $isDistinct = true): SelectQueryBuilder
    {
        $this->queryBuilder->distinct($isDistinct);

        return $this;
    }

    public function withAllRelationships(): SelectQueryBuilder
    {
        $this->relationships = array_keys($this->model->getRelationships());

        return $this;
    }

    public function withRelationships(array $relationships): SelectQueryBuilder
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function where(Closure $where): SelectQueryBuilder
    {
        $this->queryBuilder->where($where);

        return $this;
    }

    public function groupBy(string $attribute): SelectQueryBuilder
    {
        $this->queryBuilder->groupBy($attribute);

        return $this;
    }

    public function groupByAttribute(array $attributes): SelectQueryBuilder
    {
        $this->queryBuilder->groupByAttributes($attributes);

        return $this;
    }

    public function having(Closure $having): SelectQueryBuilder
    {
        $this->queryBuilder->having($having);

        return $this;
    }

    public function orderBy(string $attribute, string $direction = "ASC"): SelectQueryBuilder
    {
        $this->queryBuilder->orderBy($attribute, $direction);

        return $this;
    }

    /**
     * @param int|null $limit
     */
    public function limit($limit): SelectQueryBuilder
    {
        $this->queryBuilder->limit($limit);

        return $this;
    }

    /**
     * @param int|null $offset
     */
    public function offset($offset): SelectQueryBuilder
    {
        $this->queryBuilder->offset($offset);

        return $this;
    }

    public function getQueryBuilder(): SelectQueryBuilderInterface
    {
        return $this->queryBuilder;
    }

    public function fetchById($id): array
    {
        $this->queryBuilder
            ->where(
                function (ConditionBuilderInterface $where) use ($id) {
                    $where->columnToValue($this->model->getPrimaryKey(), "=", $id);
                }
            )
            ->limit(1);

        return $this->executor->fetchOne($this->model, $this->queryBuilder, $this->relationships);
    }

    public function fetchFirst(): array
    {
        $this->queryBuilder->limit(1);

        return $this->executor->fetchOne($this->model, $this->queryBuilder, $this->relationships);
    }

    public function fetchAll(): array
    {
        return $this->executor->fetchAll($this->model, $this->queryBuilder, $this->relationships);
    }
}
