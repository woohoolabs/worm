<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

class Executor
{
    /**
     * @var IdentityMap
     */
    private $identityMap;

    public function __construct(IdentityMap $identityMap)
    {
        $this->identityMap = $identityMap;
    }

    public function fetchOne(
        ModelInterface $model,
        SelectQueryBuilderInterface $selectQueryBuilder,
        array $relationships
    ): array {
        $entities = $this->createEntities($model, $selectQueryBuilder, $relationships);

        return empty($entities) ? [] : $entities[0];
    }

    public function fetchAll(
        ModelInterface $model,
        SelectQueryBuilderInterface $selectQueryBuilder,
        array $relationships
    ): array {
        return $this->createEntities($model, $selectQueryBuilder, $relationships);
    }

    public function getIdentityMap(): IdentityMap
    {
        return $this->identityMap;
    }

    private function createEntities(
        ModelInterface $model,
        SelectQueryBuilderInterface $selectQueryBuilder,
        array $relationships
    ): array {
        $entities = $selectQueryBuilder->fetchAll();

        foreach ($entities as $entity) {
            if (isset($entity[$model->getPrimaryKey()]) === false) {
                continue;
            }

            $this->identityMap->addId($model->getTable(), $entity[$model->getPrimaryKey()]);
        }

        return $this->matchRelationships(
            $selectQueryBuilder->getConnection(),
            $model,
            $entities,
            array_flip($relationships)
        );
    }

    private function matchRelationships(
        ConnectionInterface $connection,
        ModelInterface $model,
        array $entities,
        array $relationships
    ): array {
        $relationshipModels = array_intersect_key($model->getRelationships(), $relationships);

        foreach ($relationshipModels as $relationshipName => $relationshipClosure) {
            /** @var RelationshipInterface $relationshipModel */
            $relationshipModel = $relationshipClosure();

            $relationshipQuery = $relationshipModel->getRelationship($model, $connection, $entities);
            $relatedEntities = $relationshipQuery->fetchAll();
            $entities = $relationshipModel->matchRelationship(
                $entities,
                $relationshipName,
                $relatedEntities,
                $this->identityMap
            );
        }

        return $entities;
    }
}
