<?php
namespace WoohooLabs\Worm\Connection;

use PDO;

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
        $self = new self($dsn, $username, $password, $options);

        return $self;
    }

    protected function __construct($dsn, $username, $password, array $options)
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);
        foreach ($options as $key => $option) {
            $this->pdo->setAttribute($key, $option);
        }
    }

    public function query()
    {
        $this->pdo->query("");
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
}
