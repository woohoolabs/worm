<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Model\ModelInterface;

interface RelationshipInterface
{
    public function getParentModel(): ModelInterface;

    public function getModel(): ModelInterface;

    public function getQueryBuilder(array $entities): SelectQueryBuilderInterface;

    public function connectToParent(SelectQueryBuilderInterface $selectQueryBuilder): void;

    public function matchRelationship(
        array $entities,
        string $relationshipName,
        array $relatedEntities,
        IdentityMap $identityMap
    ): array;

    public function addRelationshipToIdentityMap(
        IdentityMap $identityMap,
        string $relationshipName,
        array $parentEntity
    ): void;

    /**
     * @param mixed $parentId
     */
    public function cascadeDelete(Persister $persister, string $relationshipName, $parentId): void;
}
