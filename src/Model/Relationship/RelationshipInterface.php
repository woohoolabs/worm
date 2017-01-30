<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\ModelContainer;
use WoohooLabs\Worm\Model\ModelInterface;

interface RelationshipInterface
{
    public function getRelationship(
        ModelInterface $model,
        ModelContainer $container,
        ConnectionInterface $connection,
        array $entities
    ): SelectQueryBuilderInterface;

    public function matchEntities(array $entities, string $relationshipName, array $relatedEntities): array;
}
