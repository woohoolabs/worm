<?php

use WoohooLabs\Worm\Examples\Model\UserModel;
use WoohooLabs\Worm\Connection\PdoConnection;
use WoohooLabs\Worm\Query\ConditionBuilder;
use WoohooLabs\Worm\Worm;

$worm = new Worm(PdoConnection::create("mysql", "localhost", "", "", "", ""));
$worm
    ->query(new UserModel())
    ->where("name", "=", "John", "and")
    ->whereNested(
        function (ConditionBuilder $condition) {
            $condition->add("name", "=", "John", "and");
        }
    )
    ->getList();
