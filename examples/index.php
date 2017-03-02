<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use WoohooLabs\Larva\Connection\MySqlPdoConnection;
use WoohooLabs\Worm\Examples\Infrastructure\Factory\ClassFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Factory\CourseFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Factory\StudentFactory;
use WoohooLabs\Worm\Examples\Infrastructure\Model\ClassModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\ClassStudentModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\CourseModel;
use WoohooLabs\Worm\Examples\Infrastructure\Model\StudentModel;
use WoohooLabs\Worm\Examples\Infrastructure\Repository\CourseRepository;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Worm;

$identityMap = new IdentityMap();
$worm = new Worm(
    new MySqlPdoConnection(
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
    ),
    $identityMap
);

// Create models
$studentModel = new StudentModel();
$classStudentModel = new ClassStudentModel();
$classModel = new ClassModel($classStudentModel, $studentModel);
$courseModel = new CourseModel($classModel);

// Instantiate factories
$studentFactory = new StudentFactory($identityMap, $studentModel);
$classFactory = new ClassFactory($identityMap, $classModel, $studentFactory);
$courseFactory = new CourseFactory($identityMap, $courseModel, $classFactory);

// Create repository
$courseRepository = new CourseRepository($worm, $courseModel, $courseFactory);

// Get courses which have classes in room number 1
$courses = $courseRepository->getCoursesInRoom(1);

echo "<pre>";
echo "Memory usage: " . (memory_get_peak_usage() / 1024 / 1024) . " MB";
echo "<h1>Courses:</h1>";

// Modify some attributes of the first course
$firstCourse = $courses[0];
$firstCourse->setName("Operating Systems Architecture 2");
$firstCourse->setDescription("Very advanced topics");

// Delete the second course
//$firstCourse->removeClass(1);

$courseRepository->save($firstCourse);

print_r($courseRepository->getCoursesInRoom(1));

echo "</pre>";
