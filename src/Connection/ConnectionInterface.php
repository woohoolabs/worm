<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Connection;

use Exception;
use WoohooLabs\Worm\Driver\TranslatorInterface;

interface ConnectionInterface
{
    /**
     * @return mixed
     */
    public function queryAll(string $sql, array $params = []);

    /**
     * @return mixed
     */
    public function query(string $sql, array $params = []);

    /**
     * @return mixed
     */
    public function execute(string $sql, array $params = []);

    /**
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * @return bool
     */
    public function commit(): bool;

    /**
     * @return bool
     */
    public function rollback(): bool;

    /**
     * @throws Exception
     */
    public function getTranslator(): TranslatorInterface;
}
