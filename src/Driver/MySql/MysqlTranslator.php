<?php
namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\TranslatorInterface;
use WoohooLabs\Worm\Query\SelectQueryInterface;

class MysqlTranslator implements TranslatorInterface
{
    /**
     * @var MysqlConditionsTranslator;
     */
    private $conditionsTranslator;

    public function __construct(MysqlConditionsTranslator $conditionsTranslator)
    {
        $this->conditionsTranslator = $conditionsTranslator;
    }

    public function translateSelectQuery(SelectQueryInterface $query)
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

        array_filter(
            $sql,
            function ($item) {
                return empty($item) === false;
            }
        );

        $text = "";
        foreach ($sql as $name => $text) {
            $text .= $name . "\n    " . $text . "\n";
        }

        return $text;
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateSelect(SelectQueryInterface $query)
    {
        if (empty($query->getSelect())) {
            $sql = "*";
        } else {
            $sql = implode(",", $query);
        }

        return $sql;
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateFrom(SelectQueryInterface $query)
    {
        return $query->getFrom();
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateJoin(SelectQueryInterface $query)
    {
        $sql = "";

        return $sql;
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateWhere(SelectQueryInterface $query)
    {
        return $this->conditionsTranslator->translateConditions($query->getWhere());
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateGroupBy(SelectQueryInterface $query)
    {
        return implode(",", $query->getGroupBy());
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateHaving(SelectQueryInterface $query)
    {
        return $this->conditionsTranslator->translateConditions($query->getHaving());
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateOrderBy(SelectQueryInterface $query)
    {
        return implode(",", $query->getOrderBy());
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateLimit(SelectQueryInterface $query)
    {
        return $query->getLimit();
    }

    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    private function translateOffset(SelectQueryInterface $query)
    {
        return $query->getOffset();
    }
}
