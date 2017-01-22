<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\SelectTranslatorInterface;
use WoohooLabs\Worm\Driver\TranslatedQuerySegment;
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

    public function translateSelectQuery(SelectQueryInterface $query): TranslatedQuerySegment
    {
        /** @var TranslatedQuerySegment[] $segments */
        $segments = [
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

        $query = new TranslatedQuerySegment();
        foreach ($segments as $name => $segment) {
            if (empty($segment->getSql())) {
                continue;
            }

            $query->add(
                $name . "\n    " . $segment->getSql() . "\n",
                $segment->getParams()
            );
        }

        return $query;
    }

    private function translateSelect(SelectQueryInterface $query): TranslatedQuerySegment
    {
        if (empty($query->getSelect())) {
            return new TranslatedQuerySegment("*");
        }

        return new TranslatedQuerySegment(implode(",", $query));
    }

    private function translateFrom(SelectQueryInterface $query): TranslatedQuerySegment
    {
        if ($query->getFrom() === "") {
            return new TranslatedQuerySegment();
        }

        return new TranslatedQuerySegment("`" . $query->getFrom() . "`");
    }

    private function translateJoin(SelectQueryInterface $query): TranslatedQuerySegment
    {
        if (empty($query->getJoin())) {
            return new TranslatedQuerySegment();
        }

        return new TranslatedQuerySegment();
    }

    private function translateWhere(SelectQueryInterface $query): TranslatedQuerySegment
    {
        return $this->conditionsTranslator->translateConditions($query->getWhere());
    }

    private function translateGroupBy(SelectQueryInterface $query): TranslatedQuerySegment
    {
        return new TranslatedQuerySegment(implode(",", $query->getGroupBy()));
    }

    private function translateHaving(SelectQueryInterface $query): TranslatedQuerySegment
    {
        return $this->conditionsTranslator->translateConditions($query->getHaving());
    }

    private function translateOrderBy(SelectQueryInterface $query): TranslatedQuerySegment
    {
        return new TranslatedQuerySegment(implode(",", $query->getOrderBy()));
    }

    private function translateLimit(SelectQueryInterface $query): TranslatedQuerySegment
    {
        if ($query->getLimit() === null) {
            return new TranslatedQuerySegment();
        }

        return new TranslatedQuerySegment("?", [$query->getLimit()]);
    }

    private function translateOffset(SelectQueryInterface $query): TranslatedQuerySegment
    {
        if ($query->getOffset() === null) {
            return new TranslatedQuerySegment();
        }

        return new TranslatedQuerySegment("?", [$query->getOffset()]);
    }
}
