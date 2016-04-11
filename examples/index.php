<?php

use WoohooLabs\Worm\Examples\Model\UserModel;
use WoohooLabs\Worm\Connection\PdoConnection;
use WoohooLabs\Worm\Worm;

$worm = new Worm(PdoConnection::create("", "", "", "", "", ""));
$worm
    ->query(new UserModel())
    ->where("name", "=", "John", "and")
    ->getList();
