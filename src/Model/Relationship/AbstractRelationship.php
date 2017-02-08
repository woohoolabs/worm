<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use Closure;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

abstract class AbstractRelationship implements RelationshipInterface
{
    /**
     * @var ModelInterface
     */
    protected $model;

    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    protected function getWhereCondition(ModelInterface $model, array $entities): Closure
    {
        return function (ConditionBuilderInterface $where) use ($model, $entities) {
            $values = [];

            foreach ($entities as $entity) {
                if (isset($entity[$model->getPrimaryKey()]) === false) {
                    continue;
                }

                $values[] = $entity[$model->getPrimaryKey()];
            }

            $where->inValues($model->getPrimaryKey(), $values, $model->getTable());
        };
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
            $this->model->getTable(),
            $entity[$this->model->getPrimaryKey()],
            $relationshipName,
            $relatedModel->getTable(),
            $relatedEntityId
        );
    }
}
