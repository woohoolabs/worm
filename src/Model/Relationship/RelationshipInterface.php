<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

interface RelationshipInterface
{
    public function getQueryBuilder(
        ModelInterface $model,
        array $entities
    ): SelectQueryBuilderInterface;

    public function matchRelationship(
        array $entities,
        string $relationshipName,
        array $relatedEntities,
        IdentityMap $identityMap
    ): array;
}
