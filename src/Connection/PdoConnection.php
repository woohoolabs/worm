<?php
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
    protected $pdo;

    public static function create(
        $driver,
        $host,
        $port,
        $database,
        $username,
        $password,
        $charset = "utf-8",
        $collation = "utf8_unicode_ci",
        $prefix = "",
        array $options = []
    ) {
        $dsn = "$driver:host=$host; dbname=$database";
        $customOptions = [];
        $self = new self($dsn, $username, $password, array_merge($options, $customOptions));

        return $self;
    }

    protected function __construct($dsn, $username, $password, array $options)
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);
        foreach ($options as $key => $option) {
            $this->pdo->setAttribute($key, $option);
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function queryAll($sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return Traversable|array
     */
    public function query($sql, array $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        if ($statement->nextRowset() === false) {
            return [];
        }

        while ($statement->nextRowset()) {
            yield $statement->fetch();
        }
    }

    public function execute()
    {
        $this->pdo->exec("");
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    /**
     * @return TranslatorInterface
     * @throws Exception
     */
    public function getTranslator()
    {
        if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === "mysql") {
            return new MysqlTranslator(new MysqlConditionsTranslator());
        }

        throw new Exception("Driver (" . $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . ") is not supported!");
    }
}
