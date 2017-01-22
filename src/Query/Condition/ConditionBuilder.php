<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Condition;

use Closure;
use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryBuilder;

class ConditionBuilder implements ConditionBuilderInterface, ConditionsInterface
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var array
     */
    private $conditions = [];

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function addColumnToValueComparison(
        string $column,
        string $operator,
        $value,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "column-value",
            "condition" => [
                "column" => $column,
                "operator" => $operator,
                "value" => $value
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addColumnToColumnComparison(
        string $column1,
        string $operator,
        string $column2,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "column-column",
            "condition" => [
                "column1" => $column1,
                "operator" => $operator,
                "column2" => $column2
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addColumnToFunctionComparison(
        string $column,
        string $operator,
        string $function,
        array $params = [],
        string $connector = "and"
    ): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "type" => "column-function",
            "condition" => [
                "column" => $column,
                "operator" => $operator,
                "function" => $function
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addFunctionToFunctionComparison(
        string $function1,
        string $operator,
        string $function2,
        string $connector = "and"
    ): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "type" => "function-function",
            "condition" => [
                "function1" => $function1,
                "operator" => $operator,
                "function2" => $function2
            ],
            "operator" => $connector
        ];
    }

    public function addIsComparison(
        string $column,
        string $value,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "is",
            "condition" => [
                "column" => $column,
                "value" => $value,
                "not" => false,
            ],
            "operator" => $connector
        ];

        return $this;
    }


    public function addIsNotComparison(
        string $column,
        string $value,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "is",
            "condition" => [
                "column" => $column,
                "value" => $value,
                "not" => true,
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addInValuesCondition(
        string $column,
        array $values,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "in-values",
            "condition" => [
                "column" => $column,
                "values" => $values,
                "not" => false,
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addNotInValuesCondition(
        string $column,
        array $values,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "in-values",
            "condition" => [
                "column" => $column,
                "values" => $values,
                "not" => true,
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addInSubselectCondition(
        string $column,
        Closure $subselect,
        string $connector = "and"
    ): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "type" => "in-subselect",
            "condition" => [
                "column" => $column,
                "subselect" => $subselect,
                "not" => false,
            ],
            "operator" => $connector
        ];
    }

    public function addNotInSubselectCondition(
        string $column,
        Closure $subselect,
        string $connector = "and"
    ): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "type" => "in-subselect",
            "condition" => [
                "column" => $column,
                "subselect" => $subselect,
                "not" => true,
            ],
            "operator" => $connector
        ];
    }

    public function addRawComparison(
        string $condition,
        array $params = [],
        string $connector = "and"
    ): ConditionBuilderInterface {
        $this->conditions[] = [
            "type" => "raw",
            "condition" => [
                "condition" => $condition,
                "params" => $params
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addNestedCondition(Closure $condition, string $connector = "and"): ConditionBuilderInterface
    {
        $conditionBuilder = new ConditionBuilder($this->connection);
        $condition($conditionBuilder);

        $this->conditions[] = [
            "type" => "nested",
            "condition" => [
                "condition" => $conditionBuilder,
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addSubselectCondition(
        string $operator,
        Closure $subselect,
        string $connector = "and"
    ): ConditionBuilderInterface {
        $subselectBuilder = new SelectQueryBuilder($this->connection);

        $subselect($subselectBuilder);

        $this->conditions[] = [
            "type" => "subselect",
            "condition" => [
                "condition" => $subselectBuilder,
                "operator" => $operator,
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }
}
