<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Repository;

use WoohooLabs\Worm\Examples\Infrastructure\Factory\CourseFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Worm;

abstract class AbstractRepository
{
    /**
     * @var Worm
     */
    protected $worm;

    public function __construct(Worm $worm)
    {
        $this->worm = $worm;
    }
}
