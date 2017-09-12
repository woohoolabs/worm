<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use DomainException;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

interface ModelInterface
{
    public function getTable(): string;

    /**
     * @return string[]
     */
    public function getPrimaryKeys(): array;

    /**
     * @return string[]
     */
    public function getRelationshipNames(): array;

    /**
     * @throws DomainException
     */
    public function getRelationship(string $name): RelationshipInterface;

    /**
     * @return mixed|mixed[]|null
     */
    public function getId(array $record);

    public function getHash(array $record): string;

    /**
     * @param mixed $id
     */
    public function getHashFromId($id): string;

    /**
     * @param mixed $id
     */
    public function createConditionBuilder($id): ConditionBuilderInterface;

    public function addRelationshipsToIdentityMap(IdentityMap $identityMap, array $entity): void;

    public function cascadeDelete(Persister $persister, $id): void;
}
