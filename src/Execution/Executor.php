<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use Traversable;
use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

class Executor
{
    /**
     * @var ModelContainer
     */
    private $container;

    public function __construct()
    {
        $this->container = new ModelContainer();
    }

    public function fetchOne(
        ModelInterface $model,
        SelectQueryBuilderInterface $selectQueryBuilder,
        array $relationships
    ): array {
        $model = $this->container->get($model);

        $entities = $this->fetchRelationships(
            $selectQueryBuilder->getConnection(),
            $model,
            $selectQueryBuilder->fetchAll(),
            array_flip($relationships)
        );

        return empty($entities) ? [] : $entities[0];
    }

    public function fetchCollection(
        ModelInterface $model,
        SelectQueryBuilderInterface $selectQueryBuilder,
        array $relationships
    ): Traversable {
        return $selectQueryBuilder->fetch();
    }

    private function fetchRelationships(
        ConnectionInterface $connection,
        ModelInterface $model,
        array $entities,
        array $relationships
    ): array {
        $relationshipModels = array_intersect_key($model->getRelationships(), $relationships);

        foreach ($relationshipModels as $relationshipName => $relationshipClosure) {
            /** @var RelationshipInterface $relationshipModel */
            $relationshipModel = $relationshipClosure();

            $relationshipQuery = $relationshipModel->getRelationship($model, $this->container, $connection, $entities);
            $relatedEntities = $relationshipQuery->fetchAll();
            $entities = $relationshipModel->matchRelationship($entities, $relationshipName, $relatedEntities);
        }

        return $entities;
    }
}
