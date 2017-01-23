<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\AbstractQueryTranslator;
use WoohooLabs\Worm\Driver\SelectTranslatorInterface;
use WoohooLabs\Worm\Driver\TranslatedQuerySegment;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

class MySqlSelectTranslator extends AbstractQueryTranslator implements SelectTranslatorInterface
{
    /**
     * @var MySqlConditionsTranslator;
     */
    private $conditionsTranslator;

    public function __construct(MySqlConditionsTranslator $conditionsTranslator)
    {
        $this->conditionsTranslator = $conditionsTranslator;
    }

    public function translateSelectQuery(SelectQueryInterface $query): TranslatedQuerySegment
    {
        return $this->compileTranslatedQuerySegments(
            [
                $this->translateSelect($query),
                $this->translateFrom($query),
                $this->translateJoins($query),
                $this->translateWhere($query),
                $this->translateGroupBy($query),
                $this->translateHaving($query),
                $this->translateOrderBy($query),
                $this->translateLimit($query),
                $this->translateOffset($query),
            ]
        );
    }

    private function translateSelect(SelectQueryInterface $query): array
    {
        $distinct = $query->isDistinct() ? " DISTINCT" : "";

        if (empty($query->getSelect())) {
            return [
                $this->createTranslatedQuerySegment("SELECT$distinct", "*")
            ];
        }

        return [
            $this->createTranslatedQuerySegment("SELECT$distinct", implode(",", $query->getSelect()))
        ];
    }

    private function translateFrom(SelectQueryInterface $query): array
    {
        $from = $query->getFrom();

        if (empty($from)) {
            return [];
        }

        $alias = empty($from["alias"]) ? "" : " AS " . $from["alias"];

        if ($from["type"] === "subquery") {
            $subselectSegment = $this->translateSelect($from["from"]);
            $subselect = $subselectSegment->getSql();

            return [
                $this->createTranslatedQuerySegment("FROM", "($subselect)$alias", $subselectSegment->getParams())
            ];
        }

        $table = $from["table"];

        return [
            $this->createTranslatedQuerySegment("FROM", "`$table`$alias")
        ];
    }

    /**
     * @param SelectQueryInterface $query
     * @return TranslatedQuerySegment[]
     */
    private function translateJoins(SelectQueryInterface $query): array
    {
        $joins = $query->getJoins();

        if (empty($joins)) {
            return [];
        }

        $segments = [];
        $params = [];
        foreach ($joins as $join) {
            if ($join["on"]) {
                $conditionSegment = $this->conditionsTranslator->translateConditions($join["on"]);
                $params = $conditionSegment->getParams();

                $on = $conditionSegment->getSql();

                $segments[] = $this->createTranslatedQuerySegment("ON", "$on", $params);
            } else {
                $type = $join["type"] ? $join["type"] : "";
                $table = $join["table"];
                $alias = empty($join["alias"]) ? "" : " AS " . $join["alias"];

                $segments[] = $this->createTranslatedQuerySegment("${type}JOIN", "`${table}`${alias}", $params);
            }
        }

        return $segments;
    }

    private function translateWhere(SelectQueryInterface $query): array
    {
        if (empty($query->getWhere()->getConditions())) {
            return [];
        }

        $result = $this->conditionsTranslator->translateConditions($query->getWhere());

        return [
            $this->createTranslatedQuerySegment("WHERE", $result->getSql(), $result->getParams())
        ];
    }

    private function translateGroupBy(SelectQueryInterface $query): array
    {
        if (empty($query->getGroupBy())) {
            return [];
        }

        return [
            $this->createTranslatedQuerySegment("GROUP BY", implode(",", $query->getGroupBy()))
        ];
    }

    private function translateHaving(SelectQueryInterface $query): array
    {
        if (empty($query->getHaving()->getConditions())) {
            return [];
        }

        $result = $this->conditionsTranslator->translateConditions($query->getHaving());

        return [
            $this->createTranslatedQuerySegment("HAVING", $result->getSql(), $result->getParams())
        ];
    }

    private function translateOrderBy(SelectQueryInterface $query): array
    {
        if (empty($query->getOrderBy())) {
            return [];
        }

        $querySegment = new TranslatedQuerySegment();
        $count = count($query->getOrderBy());
        foreach ($query->getOrderBy() as $i => $orderBy) {
            $attribute = $orderBy["attribute"];
            $direction = $orderBy["direction"] ? " " . $orderBy["direction"] : "";

            $querySegment->add("${attribute}${direction}");

            if ($i < $count - 1) {
                $querySegment->add(", ");
            }
        }

        return [
            $this->createTranslatedQuerySegment("ORDER BY", $querySegment->getSql())
        ];
    }

    private function translateLimit(SelectQueryInterface $query): array
    {
        if ($query->getLimit() === null) {
            return [];
        }

        return [
            $this->createTranslatedQuerySegment("LIMIT", "?", [$query->getLimit()])
        ];
    }

    private function translateOffset(SelectQueryInterface $query): array
    {
        if ($query->getOffset() === null) {
            return [];
        }

        return [
            $this->createTranslatedQuerySegment("OFFSET", "?", [$query->getOffset()])
        ];
    }
}
