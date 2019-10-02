<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Tests\TestDouble;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Driver\DriverInterface;
use WoohooLabs\Larva\Driver\MySql\MySqlDriver;

class DummyConnection implements ConnectionInterface
{
    public function fetchAll(string $sql, array $params = []): array
    {
        return [];
    }

    public function fetch(string $sql, array $params = []): iterable
    {
        return [];
    }

    public function fetchColumn(string $sql, array $params = [])
    {
        return null;
    }

    public function execute(string $sql, array $params = []): bool
    {
        return false;
    }

    public function beginTransaction(): bool
    {
        return true;
    }

    public function commit(): bool
    {
        return true;
    }

    public function rollback(): bool
    {
        return true;
    }

    public function getLastInsertedId(?string $name = null): string
    {
        return "";
    }

    public function getDriver(): DriverInterface
    {
        return new MySqlDriver();
    }

    public function getLog(): array
    {
        return [];
    }
}
