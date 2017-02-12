<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use DomainException;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

interface ModelInterface
{
    public function getTable(): string;

    public function getPrimaryKey(): string;

    public function isAutoIncremented(): bool;

    /**
     * @return string[]
     */
    public function getRelationshipNames(): array;

    /**
     * @throws DomainException
     */
    public function getRelationship(string $name): RelationshipInterface;
}
