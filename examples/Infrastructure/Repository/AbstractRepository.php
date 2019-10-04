<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Repository;

use WoohooLabs\Worm\Worm;

abstract class AbstractRepository
{
    protected Worm $worm;

    public function __construct(Worm $worm)
    {
        $this->worm = $worm;
    }
}
