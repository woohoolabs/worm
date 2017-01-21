<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Insert;

use WoohooLabs\Worm\Query\QueryInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

interface InsertQueryInterface extends QueryInterface
{
    public function getInto(): string;

    public function getValues(): array;

    /**
     * @return SelectQueryInterface|null
     */
    public function getSelect();
}
