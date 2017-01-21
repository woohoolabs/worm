<?php
declare(strict_types=1);

require "../vendor/autoload.php";

use WoohooLabs\Worm\Connection\PdoConnection;
use WoohooLabs\Worm\Examples\Model\StudentModel;
use WoohooLabs\Worm\Query\ConditionBuilder;
use WoohooLabs\Worm\Worm;

$worm = new Worm(
    PdoConnection::create(
        "mysql",
        "mysql",
        (int) getenv("MYSQL_PORT"),
        getenv("MYSQL_DATABASE"),
        getenv("MYSQL_USER"),
        getenv("MYSQL_PASSWORD")
    )
);

$result = $worm
    ->queryModel(new StudentModel())
    ->where("first_name", "=", "Nino", "and")
    ->whereNested(
        function (ConditionBuilder $condition) {
            $condition->add("last_name", "=", "Fillmer", "and");
        }
    )
    ->execute();

echo "<pre>";
print_r($result);
echo "</pre>";
