<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder as LarvaSelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;

class SelectQueryBuilder
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var QueryExecutor
     */
    private $queryExecutor;

    /**
     * @var LarvaSelectQueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $relationships = [];

    public function __construct(ModelInterface $model, QueryExecutor $executor)
    {
        $this->model = $model;
        $this->queryExecutor = $executor;
        $this->queryBuilder = new LarvaSelectQueryBuilder();
        $this->queryBuilder->from($model->getTable());
    }

    public function selectColumns(array $columns): SelectQueryBuilder
    {
        $this->queryBuilder->selectColumns($columns);

        return $this;
    }

    public function selectColumn(string $column): SelectQueryBuilder
    {
        $this->queryBuilder->selectColumn($column);

        return $this;
    }

    public function selectExpressions(array $expressions): SelectQueryBuilder
    {
        $this->queryBuilder->selectExpressions($expressions);

        return $this;
    }

    public function selectExpression(string $expression, string $alias = ""): SelectQueryBuilder
    {
        $this->queryBuilder->selectExpression($expression, $alias);

        return $this;
    }

    public function distinct(bool $isDistinct = true): SelectQueryBuilder
    {
        $this->queryBuilder->distinct($isDistinct);

        return $this;
    }

    public function withAllRelationships(): SelectQueryBuilder
    {
        $this->relationships = $this->model->getRelationshipNames();

        return $this;
    }

    public function withAllTransitiveRelationships(): SelectQueryBuilder
    {
        $this->relationships = $this->getModelRelationships($this->model);

        return $this;
    }

    public function withRelationships(array $relationships): SelectQueryBuilder
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function where(ConditionBuilderInterface $where): SelectQueryBuilder
    {
        $this->queryBuilder->where($where);

        return $this;
    }

    public function addWhereGroup(ConditionBuilderInterface $where): SelectQueryBuilder
    {
        $this->queryBuilder->addWhereGroup($where);

        return $this;
    }

    public function whereHas(string $relationshipName, ConditionBuilderInterface $conditionBuilder): SelectQueryBuilder
    {
        $relationship = $this->model->getRelationship($relationshipName);

        $subselect = LarvaSelectQueryBuilder::create()
            ->selectExpression("1")
            ->from($relationship->getModel()->getTable())
            ->where(
                $conditionBuilder
            );
        $relationship->connectToParent($subselect);

        $this->queryBuilder->addWhereGroup(
            ConditionBuilder::create()
                ->exists($subselect)
        );

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

    public function having(ConditionBuilderInterface $having): SelectQueryBuilder
    {
        $this->queryBuilder->having($having);

        return $this;
    }

    public function addHavingGroup(ConditionBuilderInterface $having): SelectQueryBuilder
    {
        $this->queryBuilder->addHavingGroup($having);

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

    public function lockForShare(): SelectQueryBuilder
    {
        $this->queryBuilder->lockForShare();

        return $this;
    }

    public function lockForUpdate(): SelectQueryBuilder
    {
        $this->queryBuilder->lockForUpdate();

        return $this;
    }

    public function getQueryBuilder(): SelectQueryBuilderInterface
    {
        return $this->queryBuilder;
    }

    public function fetchById($id): array
    {
        $this->queryBuilder
            ->addWhereGroup(
                ConditionBuilder::create()
                    ->columnToValue($this->model->getPrimaryKey(), "=", $id)
            )
            ->limit(1);

        return $this->queryExecutor->fetchOne($this->model, $this->queryBuilder, $this->relationships);
    }

    public function fetchFirst(): array
    {
        $this->queryBuilder->limit(1);

        return $this->queryExecutor->fetchOne($this->model, $this->queryBuilder, $this->relationships);
    }

    public function fetchAll(): array
    {
        return $this->queryExecutor->fetchAll($this->model, $this->queryBuilder, $this->relationships);
    }

    /**
     * @return mixed
     */
    public function fetchColumn()
    {
        return $this->queryExecutor->fetchColumn($this->queryBuilder);
    }

    public function fetchCount(string $column = "*"): int
    {
        $this->queryBuilder->selectCount($column);

        return $this->queryExecutor->fetchColumn($this->queryBuilder);
    }

    public function getSql(): string
    {
        return $this->queryExecutor->getSql($this->queryBuilder);
    }

    public function getParams(): array
    {
        return $this->queryExecutor->getParams($this->queryBuilder);
    }

    private function getModelRelationships(ModelInterface $model): array
    {
        $result = [];

        foreach ($model->getRelationshipNames() as $relationshipName) {
            $relationship = $model->getRelationship($relationshipName);

            if (empty($relationship->getModel()->getRelationshipNames())) {
                $result[] = $relationshipName;
            } else {
                $result[$relationshipName] = $this->getModelRelationships($relationship->getModel());
            }
        }

        return $result;
    }
}
