<?php
namespace WoohooLabs\Worm\Query\Condition;

use Closure;

interface ConditionBuilderInterface
{
    public function add(string $operand1, string $operator, string $operand2, string $connector = "and"): ConditionBuilderInterface;

    public function addRaw(string $condition, array $params = [], string $connector = "and"): ConditionBuilderInterface;

    public function addNested(Closure $condition, string $connector = "and"): ConditionBuilderInterface;
}
