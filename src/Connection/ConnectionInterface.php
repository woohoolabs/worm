<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Connection;

use Traversable;
use WoohooLabs\Worm\Driver\SelectTranslatorInterface;

interface ConnectionInterface
{
    public function queryAll(string $sql, array $params = []): array;

    public function query(string $sql, array $params = []): Traversable;

    public function execute(string $sql, array $params = []): bool;

    public function beginTransaction(): bool;

    public function commit(): bool;

    public function rollback(): bool;

    public function getDriver(): SelectTranslatorInterface;
}
