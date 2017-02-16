<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

abstract class AbstractRelationship implements RelationshipInterface
{
    /**
     * @var ModelInterface
     */
    protected $parentModel;

    public function __construct(ModelInterface $model)
    {
        $this->parentModel = $model;
    }

    public function getParentModel(): ModelInterface
    {
        return $this->parentModel;
    }

    protected function getWhereCondition(string $prefix, string $foreignKey, array $entities): ConditionBuilderInterface
    {
        $values = [];
        foreach ($entities as $entity) {
            if (isset($entity[$this->parentModel->getPrimaryKey()]) === false) {
                continue;
            }

            $values[] = $entity[$this->parentModel->getPrimaryKey()];
        }

        return ConditionBuilder::create()
            ->inValues($foreignKey, $values, $prefix);
    }

    protected function insertOneRelationship(
        array $entities,
        string $relationshipName,
        ModelInterface $relatedModel,
        array $relatedEntities,
        string $foreignKey,
        string $field,
        IdentityMap $identityMap
    ): array {
        $relatedEntityMap = $this->getEntityMapForOne($relatedEntities, $foreignKey);

        foreach ($entities as $key => $entity) {
            // Check if the entity has related entities
            if (isset($relatedEntityMap[$entity[$field]]) === false) {
                continue;
            }

            $relatedEntity = $relatedEntityMap[$entity[$field]];

            // Add the related entity to the entity
            $entities[$key][$relationshipName] = $relatedEntity;

            // Add the related entity to the identity map
            $this->addToEntityMap($entity, $relationshipName, $relatedModel, $relatedEntity, $identityMap);
        }

        return $entities;
    }

    protected function insertManyRelationship(
        array $entities,
        string $relationshipName,
        ModelInterface $relatedModel,
        array $relatedEntities,
        string $foreignKey,
        string $field,
        IdentityMap $identityMap
    ): array {
        $relatedEntityMap = $this->getEntityMapForMany($relatedEntities, $foreignKey);

        foreach ($entities as $key => $entity) {
            // Check if the entity has related entities
            if (isset($relatedEntityMap[$entity[$field]]) === false) {
                continue;
            }

            $relationship = $relatedEntityMap[$entity[$field]];

            // Add related entities to the entity
            $entities[$key][$relationshipName] = $relationship;

            // Add related entities to the identity map
            foreach ($relationship as $relatedEntity) {
                $this->addToEntityMap($entity, $relationshipName, $relatedModel, $relatedEntity, $identityMap);
            }
        }

        return $entities;
    }

    private function getEntityMapForOne(array $entities, string $field)
    {
        $entityMap = [];
        foreach ($entities as $entity) {
            if (isset($entity[$field]) === false) {
                continue;
            }

            $entityMap[$entity[$field]] = $entity;
        }

        return $entityMap;
    }

    private function getEntityMapForMany(array $entities, string $field)
    {
        $entityMap = [];
        foreach ($entities as $entity) {
            if (isset($entity[$field]) === false) {
                continue;
            }

            $entityMap[$entity[$field]][] = $entity;
        }

        return $entityMap;
    }

    private function addToEntityMap(
        array $entity,
        string $relationshipName,
        ModelInterface $relatedModel,
        array $relatedEntity,
        IdentityMap $identityMap
    ) {
        // Check for the ID of the related entity
        if (isset($relatedEntity[$relatedModel->getPrimaryKey()]) === false) {
            return;
        }

        $relatedEntityId = $relatedEntity[$relatedModel->getPrimaryKey()];

        // Add related entity to the identity map
        $identityMap->addId($relatedModel->getTable(), $relatedEntityId);

        // Add relationship to the identity map
        $identityMap->addRelatedId(
            $this->parentModel->getTable(),
            $entity[$this->parentModel->getPrimaryKey()],
            $relationshipName,
            $relatedModel->getTable(),
            $relatedEntityId
        );
    }
}
