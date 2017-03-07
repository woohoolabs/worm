<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Delete\DeleteQueryBuilderInterface;
use WoohooLabs\Larva\Query\Insert\InsertQueryBuilderInterface;
use WoohooLabs\Larva\Query\QueryBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Larva\Query\Truncate\TruncateQueryBuilderInterface;
use WoohooLabs\Larva\Query\Update\UpdateQueryBuilderInterface;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

class QueryExecutor
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    public function __construct(ConnectionInterface $connection, IdentityMap $identityMap)
    {
        $this->connection = $connection;
        $this->identityMap = $identityMap;
    }

    public function fetchOne(
        ModelInterface $model,
        SelectQueryBuilderInterface $queryBuilder,
        array $relationships
    ): array {
        $entities = $this->createEntities($model, $queryBuilder, $relationships);

        return empty($entities) ? [] : $entities[0];
    }

    public function fetchAll(
        ModelInterface $model,
        SelectQueryBuilderInterface $queryBuilder,
        array $relationships
    ): array {
        return $this->createEntities($model, $queryBuilder, $relationships);
    }

    /**
     * @return mixed
     */
    public function fetchColumn(SelectQueryBuilderInterface $queryBuilder)
    {
        return $queryBuilder->fetchColumn($this->connection);
    }

    public function insert(InsertQueryBuilderInterface $queryBuilder): bool
    {
        return $queryBuilder->execute($this->connection);
    }

    public function update(UpdateQueryBuilderInterface $queryBuilder): bool
    {
        return $queryBuilder->execute($this->connection);
    }

    public function delete(DeleteQueryBuilderInterface $queryBuilder): bool
    {
        return $queryBuilder->execute($this->connection);
    }

    public function truncate(TruncateQueryBuilderInterface $queryBuilder): bool
    {
        return $queryBuilder->execute($this->connection);
    }

    public function getSql(QueryBuilderInterface $queryBuilder): string
    {
        return $queryBuilder->getSql($this->connection);
    }

    public function getParams(QueryBuilderInterface $queryBuilder): array
    {
        return $queryBuilder->getParams($this->connection);
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
        $entities = $selectQueryBuilder->fetchAll($this->connection);

        foreach ($entities as $entity) {
            $hash = $model->getHash($entity);

            if ($hash !== "") {
                $this->identityMap->addIdentity($model->getTable(), $hash);
            }
        }

        return $this->matchRelationships(
            $model,
            $entities,
            $relationships
        );
    }

    private function matchRelationships(
        ModelInterface $model,
        array $entities,
        array $relationships
    ): array {
        if (empty($entities)) {
            return $entities;
        }

        $relationshipNames = [];
        foreach ($relationships as $key => $name) {
            $relationshipNames[$key] = is_array($name) ? $key : $name;
        }

        $relationshipModels = array_intersect($relationshipNames, $model->getRelationshipNames());

        foreach ($relationshipModels as $relationshipKey => $relationshipName) {

            /** @var RelationshipInterface $relationshipModel */
            $relationshipModel = $model->getRelationship($relationshipName);

            $relationshipQuery = $relationshipModel->getQueryBuilder($entities);
            $relatedEntities = $relationshipQuery->fetchAll($this->connection);

            if (is_array($relationships[$relationshipKey])) {
                $relatedEntities = $this->matchRelationships(
                    $relationshipModel->getModel(),
                    $relatedEntities,
                    $relationships[$relationshipKey]
                );
            }

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
