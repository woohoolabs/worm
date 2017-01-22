<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Condition;

use Closure;

interface ConditionBuilderInterface
{
    public function addColumnToValueComparison(
        string $column,
        string $operator,
        $value,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addColumnToColumnComparison(
        string $column1,
        string $operator,
        string $column2,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addColumnToFunctionComparison(
        string $column,
        string $operator,
        string $function,
        array $params = [],
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addFunctionToFunctionComparison(
        string $function1,
        string $operator,
        string $function2,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addIsComparison(
        string $column,
        string $value,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addIsNotComparison(
        string $column,
        string $value,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addInValuesCondition(
        string $column,
        array $values,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addNotInValuesCondition(
        string $column,
        array $value,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addInSubselectCondition(
        string $column,
        Closure $subselect,
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addRawComparison(
        string $condition,
        array $params = [],
        string $connector = "and"
    ): ConditionBuilderInterface;

    public function addNestedCondition(Closure $condition, string $connector = "and"): ConditionBuilderInterface;

    public function addSubselectCondition(
        string $operator,
        Closure $subselect,
        string $connector = "and"
    ): ConditionBuilderInterface;
}
