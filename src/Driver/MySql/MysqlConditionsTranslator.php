<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Query\ConditionsInterface;

class MysqlConditionsTranslator
{
    public function translateConditions(ConditionsInterface $conditions)
    {
        $sql = "";

        $conditionArray = $conditions->getConditions();
        $count = count($conditionArray);
        for ($i = 0; $i < $count; $i++) {
            $condition = $conditionArray[$i];

            if ($i) {
                $sql .= " ";
            }
            if (isset($condition["simple"])) {
                $sql .= $this->translateSimpleCondition($condition["simple"]);
            } elseif (isset($condition["raw"])) {
                $sql .= $this->translateRawCondition($condition["raw"]);
            } elseif (isset($condition["nested"])) {
                $sql .= "(" . $this->translateNestedCondition($condition["nested"]) . ")";
            }

            if ($i < $count - 1 && isset($condition["operator"])) {
                $sql .= " " . $condition["operator"];
            }
        }

        return $sql;
    }

    private function translateSimpleCondition(array $condition): string
    {
        $sql = "`" . $condition["operand1"] . "` " . $condition["operator"] . " ";

        if (is_array($condition["operand2"])) {
            $sql .= "(" . implode(",", $condition["operand2"]) . ")";
        } elseif (is_string($condition["operand2"])) {
            $sql .= "'" . $condition["operand2"] . "'";
        } else {
            $sql .= $condition["operand2"];
        }

        return $sql;
    }

    private function translateRawCondition(array $condition): string
    {
        return $condition["condition"];
    }

    private function translateNestedCondition(array $condition): string
    {
        return $this->translateConditions($condition["condition"]);
    }
}
