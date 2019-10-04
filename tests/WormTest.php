<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Tests;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Tests\TestDouble\DummyConnection;
use WoohooLabs\Worm\Tests\TestDouble\DummyModel;
use WoohooLabs\Worm\Worm;

class WormTest extends TestCase
{
    /**
     * @test
     */
    public function query(): void
    {
        $worm = $this->createWorm();

        $worm->query(new DummyModel());

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function queryDelete(): void
    {
        $worm = $this->createWorm();

        $worm->queryDelete(new DummyModel());

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function queryInsert(): void
    {
        $worm = $this->createWorm();

        $worm->queryInsert(new DummyModel());

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function queryTruncate(): void
    {
        $worm = $this->createWorm();

        $worm->queryTruncate(new DummyModel());

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function queryUpdate(): void
    {
        $worm = $this->createWorm();

        $worm->queryUpdate(new DummyModel());

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function getConnection(): void
    {
        $worm = $this->createWorm();

        $worm->getConnection();

        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function getIdentityMap(): void
    {
        $worm = $this->createWorm();

        $worm->getIdentityMap();

        $this->expectNotToPerformAssertions();
    }

    private function createWorm(): Worm
    {
        return new Worm(new DummyConnection(), new IdentityMap());
    }
}
