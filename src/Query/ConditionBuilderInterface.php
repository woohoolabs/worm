<?php
namespace WoohooLabs\Worm\Query;

use Closure;

interface ConditionBuilderInterface
{
    /**
     * @param string $operand1
     * @param string $operator
     * @param string|array $operand2
     * @param string $connector
     * @return $this
     */
    public function add($operand1, $operator, $operand2, $connector = "and");

    /**
     * @param string $condition
     * @param string $connector
     * @return $this
     */
    public function addRaw($condition, array $params = [], $connector = "and");

    /**
     * @param Closure $condition
     * @param string $connector
     * @return $this
     */
    public function addNested(Closure $condition, $connector = "and");
}
