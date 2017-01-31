<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\ModelContainer;
use WoohooLabs\Worm\Model\ModelInterface;

class BelongsToManyRelationship extends BelongsToOneRelationship
{
    public function matchRelationship(array $entities, string $relationshipName, array $relatedEntities): array
    {
        $relatedEntities = $this->getEntityMapForMany($relatedEntities, $this->referencedKey);

        return $this->insertRelationship($entities, $relationshipName, $relatedEntities, $this->foreignKey);
    }
}
