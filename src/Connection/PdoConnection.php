<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Connection;

use Exception;
use PDO;
use Traversable;
use WoohooLabs\Worm\Driver\Mysql\MysqlConditionsTranslator;
use WoohooLabs\Worm\Driver\Mysql\MysqlTranslator;
use WoohooLabs\Worm\Driver\TranslatorInterface;

class PdoConnection implements ConnectionInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    private $fetchStyle = PDO::FETCH_ASSOC;

    public static function create(
        string $driver,
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci",
        string $prefix = "",
        array $options = []
    ): ConnectionInterface {
        $dsn = "$driver:host=$host; dbname=$database;port=$port;charset=$charset";

        $defaultOptions = [
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new self($dsn, $username, $password, array_merge($defaultOptions, $options));
    }

    protected function __construct(string $dsn, string $username, string $password, array $options)
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);

        foreach ($options as $key => $option) {
            $this->pdo->setAttribute($key, $option);
        }
    }

    /**
     * @return mixed
     */
    public function queryAll(string $sql, array $params = [])
    {
        echo "<pre>" . $sql . "</pre>";

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll($this->fetchStyle);
    }

    /**
     * @return Traversable|array
     */
    public function query(string $sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        if ($statement->nextRowset() === false) {
            return [];
        }

        while ($statement->nextRowset()) {
            yield $statement->fetch($this->fetchStyle);
        }
    }

    public function execute(string $sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @throws Exception
     */
    public function getTranslator(): TranslatorInterface
    {
        if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === "mysql") {
            return new MysqlTranslator(new MysqlConditionsTranslator());
        }

        throw new Exception("Driver (" . $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . ") is not supported!");
    }
}
