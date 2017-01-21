<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Worm\Connection\ConnectionInterface;

interface SelectQueryInterface
{
    public function getConnection(): ConnectionInterface;

    public function getSelect(): array;

    public function getFrom(): string;

    public function getJoin(): array;

    public function getWhere(): ConditionBuilder;

    public function getGroupBy(): array;

    public function getHaving(): ConditionBuilder;

    public function getOrderBy(): array;

    /**
     * @return int|null
     */
    public function getLimit();

    /**
     * @return int|null
     */
    public function getOffset();
}
