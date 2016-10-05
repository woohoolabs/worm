<?php
namespace WoohooLabs\Worm\Connection;

use Exception;
use WoohooLabs\Worm\Driver\TranslatorInterface;

interface ConnectionInterface
{
    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function queryAll($sql, array $params = []);

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, array $params = []);

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

    /**
     * @return TranslatorInterface
     * @throws Exception
     */
    public function getTranslator();
}
