<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\Insert\InsertQueryInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

class Driver implements DriverInterface
{
    /**
     * @var SelectTranslatorInterface
     */
    private $selectTranslator;

    /**
     * @var InsertTranslatorInterface
     */
    private $insertTranslator;

    public function __construct(
        SelectTranslatorInterface $selectTranslator,
        InsertTranslatorInterface $insertTranslator
    ) {
        $this->selectTranslator = $selectTranslator;
        $this->insertTranslator = $insertTranslator;
    }

    public function translateSelectQuery(SelectQueryInterface $query): string
    {
        return $this->selectTranslator->translateSelectQuery($query);
    }

    public function translateInsertQuery(InsertQueryInterface $query): string
    {
        return $this->insertTranslator->translateInsertQuery($query, $this->selectTranslator);
    }
}
