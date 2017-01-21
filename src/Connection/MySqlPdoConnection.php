<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Connection;

use WoohooLabs\Worm\Driver\Mysql\MysqlConditionsTranslator;
use WoohooLabs\Worm\Driver\Mysql\MysqlSelectTranslator;
use WoohooLabs\Worm\Driver\SelectTranslatorInterface;

class MySqlPdoConnection extends AbstractPdoConnection
{
    public static function create(
        string $driver,
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci",
        array $modes = [],
        array $options = []
    ): ConnectionInterface {
        $dsn = "$driver:host=$host;dbname=$database;port=$port;charset=$charset";

        $self = new self($dsn, $username, $password, $options);

        self::setCharset($self, $charset, $collation);
        self::setModes($self, $modes);

        return $self;
    }

    public function getDriver(): SelectTranslatorInterface
    {
        return new MysqlSelectTranslator(new MysqlConditionsTranslator());
    }

    private static function setCharset(MySqlPdoConnection $connection, string $charset, string $collation)
    {
        if (empty($charset)) {
            return;
        }

        $collation = $collation ? $collation : "";
        $connection->execute("SET NAMES '$charset' COLLATE '$collation'");
    }

    private static function setModes(MySqlPdoConnection $connection, array $modes)
    {
        if (empty($modes)) {
            $modes = [
                "ONLY_FULL_GROUP_BY",
                "STRICT_TRANS_TABLES",
                "NO_ZERO_IN_DATE",
                "NO_ZERO_DATE",
                "ERROR_FOR_DIVISION_BY_ZERO",
                "NO_AUTO_CREATE_USER",
                "NO_ENGINE_SUBSTITUTION",
            ];
        }

        $modesString = implode(",", $modes);
        $connection->execute("SET SESSION SQL_MODE='$modesString'");
    }
}
