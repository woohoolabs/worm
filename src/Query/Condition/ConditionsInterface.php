<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Condition;

interface ConditionsInterface
{
    public function getConditions(): array;
}
