<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use Closure;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;

abstract class AbstractRelationship implements RelationshipInterface
{
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

    protected function getEntityMapForOne(array $entities, string $field)
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

    protected function getEntityMapForMany(array $entities, string $field)
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

    protected function insertRelationship(
        array $entities,
        string $relationshipName,
        array $relatedEntities,
        string $field
    ): array {
        foreach ($entities as $key => $entity) {
            if (isset($relatedEntities[$entity[$field]]) === false) {
                continue;
            }

            $entities[$key][$relationshipName] = $relatedEntities[$entity[$field]];
        }

        return $entities;
    }
}
