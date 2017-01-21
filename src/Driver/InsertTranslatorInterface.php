<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\Insert\InsertQueryInterface;

interface InsertTranslatorInterface
{
    public function translateInsertQuery(InsertQueryInterface $query): string;
}
