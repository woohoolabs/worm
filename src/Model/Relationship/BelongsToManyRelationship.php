<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

class BelongsToManyRelationship extends BelongsToOneRelationship
{
    public function matchRelationship(array $entities, string $relationshipName, array $relatedEntities): array
    {
        $relatedEntities = $this->getEntityMapForMany($relatedEntities, $this->referencedKey);

        return $this->insertRelationship($entities, $relationshipName, $relatedEntities, $this->foreignKey);
    }
}
