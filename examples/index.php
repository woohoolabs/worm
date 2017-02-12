<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use WoohooLabs\Larva\Connection\MySqlPdoConnection;
use WoohooLabs\Worm\Examples\Infrastructure\Model\ClassModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\ClassStudentModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\StudentModel;
use WoohooLabs\Worm\Worm;

$worm = new Worm(
    MySqlPdoConnection::create(
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

$result1 = $worm
    ->queryModel(new StudentModel(new ClassStudentModel(), new ClassModel(new CourseModel())))
    ->withAllRelationships()
    ->fetchById(1);

echo "<pre>";
echo "<h1>RESULT SET 1:</h1>";
print_r($result1);

print_r($worm->getIdentityMap()->getMap());
echo "</pre>";
