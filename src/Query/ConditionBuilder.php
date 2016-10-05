<?php
namespace WoohooLabs\Worm\Query;

use Closure;

class ConditionBuilder implements ConditionBuilderInterface, ConditionsInterface
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function add($operand1, $operator, $operand2, $connector = "and")
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

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function addRaw($condition, array $params = [], $connector = "and")
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

    public function addNested(Closure $condition, $connector = "and")
    {
        $this->conditions[] = [
            "nested" => [
                "condition" => $condition(new ConditionBuilder()),
            ],
            "operator" => $connector
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
