<?php
namespace WoohooLabs\Worm\Driver\Mysql;

use WoohooLabs\Worm\Driver\InsertTranslatorInterface;
use WoohooLabs\Worm\Driver\SelectTranslatorInterface;
use WoohooLabs\Worm\Query\Insert\InsertQueryInterface;

class MySqlInsertTranslator implements InsertTranslatorInterface
{
    /**
     * @var SelectTranslatorInterface
     */
    private $selectTranslator;

    public function __construct(SelectTranslatorInterface $selectTranslator)
    {
        $this->selectTranslator = $selectTranslator;
    }

    public function translateInsertQuery(InsertQueryInterface $query): string
    {
        return "";
    }
}
