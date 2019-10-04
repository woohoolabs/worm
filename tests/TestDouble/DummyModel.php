<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Tests\TestDouble;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Model\Relationship\HasOneRelationship;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

class DummyModel implements ModelInterface
{
    public function getTable(): string
    {
        return "";
    }

    public function getPrimaryKeys(): array
    {
        return [];
    }

    public function getRelationshipNames(): array
    {
        return [];
    }

    public function getRelationship(string $name): RelationshipInterface
    {
        return new HasOneRelationship(new DummyModel(), new DummyModel(), "", "");
    }

    public function getId(array $record)
    {
        return "";
    }

    public function getHash(array $record): string
    {
        return "";
    }

    public function getHashFromId($id): string
    {
        return "";
    }

    public function createConditionBuilder($id): ConditionBuilderInterface
    {
        return new ConditionBuilder();
    }

    public function addRelationshipsToIdentityMap(IdentityMap $identityMap, array $entity): void
    {
    }

    public function cascadeDelete(Persister $persister, $id): void
    {
    }
}
