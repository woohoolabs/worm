<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use LogicException;
use WoohooLabs\Larva\Query\Condition\ConditionBuilder as LarvaConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;

class ConditionBuilder
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var LarvaConditionBuilder
     */
    private $conditionBuilder;

    public static function create(ModelInterface $model = null): ConditionBuilder
    {
        return new ConditionBuilder($model);
    }

    public function __construct(ModelInterface $model = null)
    {
        $this->model = $model;
        $this->conditionBuilder = new LarvaConditionBuilder();
    }

    /**
     * @param mixed $value
     */
    public function columnToValue(
        string $column,
        string $operator,
        $value,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->columnToValue($column, $operator, $value, $columnPrefix);

        return $this;
    }

    public function columnToColumn(
        string $column1,
        string $operator,
        string $column2,
        string $column1Prefix = "",
        string $column2Prefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->columnToColumn($column1, $operator, $column2, $column1Prefix, $column2Prefix);

        return $this;
    }

    public function columnToExpression(
        string $column,
        string $operator,
        string $expression,
        array $params = [],
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->columnToExpression($column, $operator, $expression, $params, $columnPrefix);

        return $this;
    }

    public function expressionToExpression(
        string $expression1,
        string $operator,
        string $expression2,
        array $params = []
    ): ConditionBuilder {
        $this->conditionBuilder->expressionToExpression($expression1, $operator, $expression2, $params);

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function is(string $column, $value, string $columnPrefix = ""): ConditionBuilder
    {
        $this->conditionBuilder->is($column, $value, $columnPrefix);

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function isNot(string $column, $value, string $columnPrefix = ""): ConditionBuilder
    {
        $this->conditionBuilder->isNot($column, $value, $columnPrefix);

        return $this;
    }

    /**
     * @param mixed[] $values
     */
    public function inValues(string $column, array $values, string $columnPrefix = ""): ConditionBuilder
    {
        $this->conditionBuilder->inValues($column, $values, $columnPrefix);

        return $this;
    }

    /**
     * @param mixed[] $values
     */
    public function notInValues(string $column, array $values, string $columnPrefix = ""): ConditionBuilder
    {
        $this->conditionBuilder->notInValues($column, $values, $columnPrefix);

        return $this;
    }

    public function inSubselect(
        string $column,
        SelectQueryBuilderInterface $subselect,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->inSubselect($column, $subselect, $columnPrefix);

        return $this;
    }

    public function notInSubselect(
        string $column,
        SelectQueryBuilderInterface $subselect,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->notInSubselect($column, $subselect, $columnPrefix);

        return $this;
    }

    public function exists(SelectQueryBuilderInterface $subselect): ConditionBuilder
    {
        $this->conditionBuilder->exists($subselect);

        return $this;
    }

    public function notExists(SelectQueryBuilderInterface $subselect): ConditionBuilder
    {
        $this->conditionBuilder->notExists($subselect);

        return $this;
    }

    public function some(
        string $column,
        string $operator,
        SelectQueryBuilderInterface $subselect,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->some($column, $operator, $subselect, $columnPrefix);

        return $this;
    }

    public function any(
        string $column,
        string $operator,
        SelectQueryBuilderInterface $subselect,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->any($column, $operator, $subselect, $columnPrefix);

        return $this;
    }

    public function all(
        string $column,
        string $operator,
        SelectQueryBuilderInterface $subselect,
        string $columnPrefix = ""
    ): ConditionBuilder {
        $this->conditionBuilder->all($column, $operator, $subselect, $columnPrefix);

        return $this;
    }

    public function raw(string $condition, array $params = []): ConditionBuilder
    {
        $this->conditionBuilder->raw($condition, $params);

        return $this;
    }

    public function nested(ConditionBuilder $condition): ConditionBuilder
    {
        $this->conditionBuilder->nested($condition->conditionBuilder);

        return $this;
    }

    public function has(
        string $relationshipName,
        ConditionBuilder $conditionBuilder
    ): ConditionBuilder {
        if ($this->model === null) {
            throw new LogicException(
                "You must provide a \"ModelInterface\" instance as a constructor parameter of the ConditionBuilder" .
                "in order to use the \"has\" condition"
            );
        }

        $relationship = $this->model->getRelationship($relationshipName);

        $subselect = SelectQueryBuilder::create()
            ->selectExpression("1")
            ->from($relationship->getModel()->getTable())
            ->where(
                $conditionBuilder->conditionBuilder
            );

        $relationship->connectToParent($subselect);

        $this->conditionBuilder->exists($subselect);

        return $this;
    }

    public function and(): ConditionBuilder
    {
        $this->conditionBuilder->and();

        return $this;
    }

    public function or(): ConditionBuilder
    {
        $this->conditionBuilder->or();

        return $this;
    }

    public function operator(string $operator): ConditionBuilder
    {
        $this->conditionBuilder->operator($operator);

        return $this;
    }

    public function getConditionBuilder(): ConditionBuilderInterface
    {
        return $this->conditionBuilder;
    }
}
