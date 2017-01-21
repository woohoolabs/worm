<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use Closure;

class ConditionBuilder implements ConditionBuilderInterface, ConditionsInterface
{
    /**
     * @var array
     */
    protected $conditions = [];

    public function add(string $operand1, string $operator, string $operand2, string $connector = "and"): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "simple" => [
                "operand1" => $operand1,
                "operator" => $operator,
                "operand2" => $operand2
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addRaw(string $condition, array $params = [], string $connector = "and"): ConditionBuilderInterface
    {
        $this->conditions[] = [
            "raw" => [
                "condition" => $condition,
                "params" => $params
            ],
            "operator" => $connector
        ];

        return $this;
    }

    public function addNested(Closure $condition, string $connector = "and"): ConditionBuilderInterface
    {
        $conditionBuilder = new ConditionBuilder();
        $result = $condition($conditionBuilder);
        if ($result) {
            $conditionBuilder = $result;
        }

        $this->conditions[] = [
            "nested" => [
                "condition" => $conditionBuilder,
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
