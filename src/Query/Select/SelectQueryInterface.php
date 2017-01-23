<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Select;

use WoohooLabs\Worm\Query\Condition\ConditionsInterface;
use WoohooLabs\Worm\Query\QueryInterface;

interface SelectQueryInterface extends QueryInterface
{
    public function getSelect(): array;

    public function isDistinct(): bool;

    public function getFrom(): array;

    public function getJoins(): array;

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
