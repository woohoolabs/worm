<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

class HasManyRelationship extends HasOneRelationship
{
    public function matchRelationship(array $entities, string $relationshipName, array $relatedEntities): array
    {
        $relatedEntities = $this->getEntityMapForMany($relatedEntities, $this->foreignKey);

        return $this->insertRelationship($entities, $relationshipName, $relatedEntities, $this->referencedKey);
    }
}
