<?php
namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\SelectQueryInterface;

interface TranslatorInterface
{
    /**
     * @param SelectQueryInterface $query
     * @return string
     */
    public function translateSelectQuery(SelectQueryInterface $query);
}
