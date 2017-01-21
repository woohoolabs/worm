<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Insert;

use Closure;

interface InsertQueryBuilderInterface
{
    public function into(string $table): InsertQueryBuilderInterface;

    public function values(array $values): InsertQueryBuilderInterface;

    public function select(Closure $select): InsertQueryBuilderInterface;

    public function execute(): bool;
}
