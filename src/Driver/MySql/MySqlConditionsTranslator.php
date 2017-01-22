<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\TranslatedQuerySegment;
use WoohooLabs\Worm\Query\Condition\ConditionsInterface;

class MySqlConditionsTranslator
{
    public function translateConditions(ConditionsInterface $conditions): TranslatedQuerySegment
    {
        $querySegment = new TranslatedQuerySegment();

        $conditionArray = $conditions->getConditions();
        $count = count($conditionArray);
        for ($i = 0; $i < $count; $i++) {
            $condition = $conditionArray[$i];

            if ($i) {
                $querySegment->add(" ");
            }

            switch ($condition["type"]) {
                case "column-value":
                    $this->translateColumnToValueCondition($querySegment, $condition["condition"]);
                    break;
                case "column-column":
                    $this->translateColumnToColumnCondition($querySegment, $condition["condition"]);
                    break;
                case "is":
                    $this->translateIsCondition($querySegment, $condition["condition"]);
                    break;
                case "in-values":
                    $this->translateInValues($querySegment, $condition["condition"]);
                    break;
                case "raw":
                    $this->translateRawCondition($querySegment, $condition["condition"]);
                    break;
                case "nested":
                    $this->translateNestedCondition($querySegment, $condition["condition"]);
                    break;
                case "subselect":
                    $this->translateSubselectCondition($querySegment, $condition["condition"]);
                    break;
            }

            if ($i < $count - 1 && isset($condition["operator"])) {
                $querySegment->add(" " . $condition["operator"]);
            }
        }

        return $querySegment;
    }

    private function translateColumnToValueCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $column = $condition["column"];
        $operator = $condition["operator"];
        $value = $condition["value"];

        $querySegment->add("`$column` $operator ?", [$value]);
    }

    private function translateIsCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $column = $condition["column"];
        $negation = $condition["not"] ? " NOT" : "";
        $value = $condition["value"];

        $querySegment->add("`$column` IS$negation $value");
    }

    private function translateInValues(TranslatedQuerySegment $querySegment, array $condition)
    {
        $column = $condition["column"];
        $negation = $condition["not"] ? "NOT " : "";
        $values = $condition["values"];
        $valuePattern = $params = implode(",", array_fill(0, count($values), "?"));

        $querySegment->add("`$column` ${negation}IN ($valuePattern)", $values);
    }

    private function translateColumnToColumnCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $column1 = $condition["column1"];
        $operator = $condition["operator"];
        $column2 = $condition["column2"];

        $querySegment->add("`$column1` $operator `$column2`");
    }

    private function translateRawCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $querySegment->add($condition["condition"], $condition["params"]);
    }

    private function translateNestedCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $nestedSegment = $this->translateConditions($condition["condition"]);

        $querySegment->add("(" . $nestedSegment->getSql() . ")", $nestedSegment->getParams());
    }

    private function translateSubselectCondition(TranslatedQuerySegment $querySegment, array $condition)
    {
        $subselectSegment = $this->translateConditions($condition["condition"]);

        $querySegment->add("(" . $subselectSegment->getSql() . ")", $subselectSegment->getParams());
    }
}
