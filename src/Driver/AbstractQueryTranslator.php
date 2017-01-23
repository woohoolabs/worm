<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

abstract class AbstractQueryTranslator
{
    protected function compileTranslatedQuerySegments(array $querySegments): TranslatedQuerySegment
    {
        $query = new TranslatedQuerySegment();

        foreach ($querySegments as $name => $segments) {
            foreach ($segments as $segment) {
                /** @var TranslatedQuerySegment $segment */
                if (empty($segment->getSql())) {
                    continue;
                }

                $query->add($segment->getSql() . "\n", $segment->getParams());
            }
        }

        return $query;
    }

    protected function createTranslatedQuerySegment(string $name = "", string $sql = "", array $params = []): TranslatedQuerySegment
    {
        return new TranslatedQuerySegment($name . "\n\t" . $sql, $params);
    }
}
