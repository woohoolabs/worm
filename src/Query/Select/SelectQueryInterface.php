<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Select;

use WoohooLabs\Worm\Query\Condition\ConditionsInterface;
use WoohooLabs\Worm\Query\QueryInterface;

interface SelectQueryInterface extends QueryInterface
{
    public function getSelect(): array;

    public function getFrom(): string;

    public function getJoin(): array;

    public function getWhere(): ConditionsInterface;

    public function getGroupBy(): array;

    public function getHaving(): ConditionsInterface;

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
