<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Model\ModelInterface;

abstract class AbstractRelationship implements RelationshipInterface
{
    /**
     * @var ModelInterface
     */
    protected $parentModel;

    /**
     * @var bool
     */
    protected $cascadedDelete;

    public function __construct(ModelInterface $parentModel, bool $isCascadedDelete)
    {
        $this->parentModel = $parentModel;
        $this->cascadedDelete = $isCascadedDelete;
    }

    public function getParentModel(): ModelInterface
    {
        return $this->parentModel;
    }

    public function cascadeDelete(Persister $persister, string $relationshipName, $parentId)
    {
        if ($this->cascadedDelete === false) {
            return;
        }

        $identityMap = $persister->getIdentityMap();
        $relatedIds = $identityMap->getRelatedIds($this->parentModel->getTable(), $parentId, $relationshipName);

        foreach ($relatedIds as $relatedId) {
            $type = $this->getModel()->getTable();
            $identityMap->setState($type, $relatedId, IdentityMap::STATE_DELETED);
            $identityMap->setObject($type, $relatedId, null);
            $identityMap->removeRelatedId($type, $parentId, $relationshipName, $relatedId);
            $this->getModel()->cascadeDelete($persister, $relatedId);
        }
    }

    protected function getWhereCondition(string $prefix, string $foreignKey, array $entities): ConditionBuilderInterface
    {
        $values = [];
        foreach ($entities as $entity) {
            $id = $this->parentModel->getId($entity);

            if ($id !== null) {
                $values[] = $id;
            }
        }

        return ConditionBuilder::create()
            ->inValues($foreignKey, $values, $prefix);
    }

    protected function insertOneRelationship(
        array $entities,
        string $relationshipName,
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
            $this->addOneToEntityMap($identityMap, $relationshipName, $entity, $relatedEntity);
        }

        return $entities;
    }

    protected function insertManyRelationship(
        array $entities,
        string $relationshipName,
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
            $this->addManyToEntityMap($identityMap, $relationshipName, $entity, $relationship);
        }

        return $entities;
    }

    protected function addManyToEntityMap(
        IdentityMap $identityMap,
        string $relationshipName,
        array $entity,
        array $relatedEntities
    ) {
        foreach ($relatedEntities as $relatedEntity) {
            $this->addOneToEntityMap($identityMap, $relationshipName, $entity, $relatedEntity);
        }
    }

    protected function addOneToEntityMap(
        IdentityMap $identityMap,
        string $relationshipName,
        array $entity,
        array $relatedEntity
    ) {
        $relatedEntityType = $this->getModel()->getTable();
        $relatedEntityId = $this->getModel()->getId($relatedEntity);
        if ($relatedEntityId === null) {
            return;
        }

        // Add related entity to the identity map
        $identityMap->addId($relatedEntityType, $relatedEntityId);

        // Add relationship to the identity map
        $identityMap->addRelatedId(
            $this->parentModel->getTable(),
            $this->parentModel->getId($entity),
            $relationshipName,
            $relatedEntityType,
            $relatedEntityId
        );
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
}
