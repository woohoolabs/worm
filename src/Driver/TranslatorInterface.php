<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\SelectQueryInterface;

interface TranslatorInterface
{
    public function translateSelectQuery(SelectQueryInterface $query): string;
}
