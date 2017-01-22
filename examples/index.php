<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use WoohooLabs\Worm\Connection\MySqlPdoConnection;
use WoohooLabs\Worm\Query\Condition\ConditionBuilder;
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

$result = $worm
    ->query()
    ->from("students", "s")
    ->where(
        function (ConditionBuilder $where) {
            $where
                ->raw("last_name LIKE ?", ["%a%"])
                ->and()
                ->nested(
                    function (ConditionBuilder $where) {
                        $where
                            ->is("birthday", null, "s")
                            ->or()
                            ->is("gender", null, "s");
                    }
                );
        }
    )
    ->limit(10)
    ->offset(0)
    ->execute();

echo "Query Log:<br/>";
echo "<pre>";
print_r($worm->getLog());
echo "</pre>";

echo "Result Set:<br/>";
echo "<pre>";
print_r($result);
echo "</pre>";
