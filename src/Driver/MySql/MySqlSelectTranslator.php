<?php
namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\SelectTranslatorInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

class MySqlSelectTranslator implements SelectTranslatorInterface
{
    /**
     * @var MySqlConditionsTranslator;
     */
    private $conditionsTranslator;

    public function __construct(MySqlConditionsTranslator $conditionsTranslator)
    {
        $this->conditionsTranslator = $conditionsTranslator;
    }

    public function translateSelectQuery(SelectQueryInterface $query): string
    {
        $sql = [
            "SELECT" => $this->translateSelect($query),
            "FROM" => $this->translateFrom($query),
            "JOIN" => $this->translateJoin($query),
            "WHERE" => $this->translateWhere($query),
            "GROUP BY" => $this->translateGroupBy($query),
            "HAVING" => $this->translateHaving($query),
            "ORDER BY" => $this->translateOrderBy($query),
            "LIMIT" => $this->translateLimit($query),
            "OFFSET" => $this->translateOffset($query),
        ];

        $sql = array_filter(
            $sql,
            function ($item) {
                return empty($item) === false;
            }
        );

        $text = "";
        foreach ($sql as $name => $value) {
            $text .= $name . "\n    " . $value . "\n";
        }

        return $text;
    }

    private function translateSelect(SelectQueryInterface $query): string
    {
        if (empty($query->getSelect())) {
            $sql = "*";
        } else {
            $sql = implode(",", $query);
        }

        return $sql;
    }

    private function translateFrom(SelectQueryInterface $query): string
    {
        return "`" . $query->getFrom() . "`";
    }

    private function translateJoin(SelectQueryInterface $query): string
    {
        $sql = "";

        return $sql;
    }

    private function translateWhere(SelectQueryInterface $query): string
    {
        return $this->conditionsTranslator->translateConditions($query->getWhere());
    }

    private function translateGroupBy(SelectQueryInterface $query): string
    {
        return implode(",", $query->getGroupBy());
    }

    private function translateHaving(SelectQueryInterface $query): string
    {
        return $this->conditionsTranslator->translateConditions($query->getHaving());
    }

    private function translateOrderBy(SelectQueryInterface $query): string
    {
        return implode(",", $query->getOrderBy());
    }

    private function translateLimit(SelectQueryInterface $query): string
    {
        return $query->getLimit() !== null ? (string) $query->getLimit() : "";
    }

    private function translateOffset(SelectQueryInterface $query): string
    {
        return $query->getOffset() !== null ? (string) $query->getOffset() : "";
    }
}
