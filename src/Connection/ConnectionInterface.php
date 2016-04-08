<?php
namespace WoohooLabs\Worm\Connection;

interface ConnectionInterface
{
    public function query();

    public function execute();

    /**
     * @return bool
     */
    public function beginTransaction();

    /**
     * @return bool
     */
    public function commit();

    /**
     * @return bool
     */
    public function rollback();
}
