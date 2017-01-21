<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Connection;

use PDO;
use Traversable;
use WoohooLabs\Worm\Logger\Logger;

abstract class AbstractPdoConnection implements ConnectionInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    private $fetchStyle = PDO::FETCH_ASSOC;

    /**
     * @var Logger
     */
    private $logger;

    protected function __construct(
        string $dsn,
        string $username,
        string $password,
        array $options,
        Logger $logger = null
    ) {
        $this->pdo = new PDO($dsn, $username, $password, $options);

        foreach ($options as $key => $option) {
            $this->pdo->setAttribute($key, $option);
        }

        $this->logger = $logger ? $logger : new Logger(false);
    }

    public function queryAll(string $sql, array $params = []): array
    {
        echo "<pre>" . $sql . "</pre>";

        $statement = $this->pdo->prepare($sql);
        $result = $statement->execute($params);

        $this->logger->log($sql, $result);

        return $statement->fetchAll($this->fetchStyle);
    }

    public function query(string $sql, array $params = []): Traversable
    {
        $statement = $this->pdo->prepare($sql);
        $result = $statement->execute($params);

        $this->logger->log($sql, $result);

        if ($statement->nextRowset() === false) {
            return [];
        }

        while ($statement->nextRowset()) {
            yield $statement->fetch($this->fetchStyle);
        }
    }

    public function execute(string $sql, array $params = []): bool
    {
        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function beginTransaction(): bool
    {
        $result = $this->pdo->beginTransaction();

        $this->logger->log("BEGIN", $result);

        return $result;
    }

    public function commit(): bool
    {
        $result = $this->pdo->commit();

        $this->logger->log("COMMIT", $result);

        return $result;
    }

    public function rollback(): bool
    {
        $result = $this->pdo->rollBack();

        $this->logger->log("ROLLBACK", $result);

        return $result;
    }

    public function getLog(): array
    {
        return $this->logger->getLog();
    }

    protected function getDefaultAttributes(): array
    {
        return [
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }
}
