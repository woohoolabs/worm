<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Worm\Execution\IdentityMap;

class BelongsToManyRelationship extends BelongsToOneRelationship
{
    public function matchRelationship(
        array $entities,
        string $relationshipName,
        array $relatedEntities,
        IdentityMap $identityMap
    ): array {
        return $this->insertManyRelationship(
            $entities,
            $relationshipName,
            $this->relatedModel,
            $relatedEntities,
            $this->referencedKey,
            $this->foreignKey,
            $identityMap
        );
    }
}
