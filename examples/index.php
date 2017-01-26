<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use WoohooLabs\Larva\Connection\MySqlPdoConnection;
use WoohooLabs\Worm\Worm;

$worm = new Worm(
    MySqlPdoConnection::create(
        "mysql",
        "mysql",
        (int) getenv("MYSQL_PORT"),
        getenv("MYSQL_DATABASE"),
        getenv("MYSQL_USER"),
        getenv("MYSQL_PASSWORD"),
        "utf8mb4",
        "utf8mb4_unicode_ci",
        [],
        [],
        true
    )
);
