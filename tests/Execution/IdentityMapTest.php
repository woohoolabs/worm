<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Tests\Execution;

use PHPUnit\Framework\TestCase;
use stdClass;
use WoohooLabs\Worm\Execution\IdentityMap;

class IdentityMapTest extends TestCase
{
    /**
     * @test
     */
    public function hasObjectWhenFalse(): void
    {
        $identityMap = new IdentityMap();

        $this->assertFalse($identityMap->hasObject("abc", "123"));
    }

    /**
     * @test
     */
    public function hasObjectWhenTrue(): void
    {
        $identityMap = new IdentityMap();
        $identityMap->addIdentity("abc", "123", new stdClass());

        $this->assertTrue($identityMap->hasObject("abc", "123"));
    }
}
