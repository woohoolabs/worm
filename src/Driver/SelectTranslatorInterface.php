<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Driver;

use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

interface SelectTranslatorInterface
{
    public function translateSelectQuery(SelectQueryInterface $query): string;
}
