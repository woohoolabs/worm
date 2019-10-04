<?php

declare(strict_types=1);

namespace WoohooLabs\Worm;

use Throwable;
use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\DeleteQueryBuilder;
use WoohooLabs\Worm\Query\InsertQueryBuilder;
use WoohooLabs\Worm\Query\SelectQueryBuilder;
use WoohooLabs\Worm\Query\TruncateQueryBuilder;
use WoohooLabs\Worm\Query\UpdateQueryBuilder;

class Worm
{
    private ConnectionInterface $connection;
    private QueryExecutor $queryExecutor;
    private Persister $persister;

    public function __construct(ConnectionInterface $connection, IdentityMap $identityMap)
    {
        $this->connection = $connection;
        $this->queryExecutor = new QueryExecutor($connection, $identityMap);
        $this->persister = new Persister($connection, $identityMap);
    }

    public function query(ModelInterface $model): SelectQueryBuilder
    {
        return new SelectQueryBuilder($model, $this->queryExecutor);
    }

    public function queryInsert(ModelInterface $model): InsertQueryBuilder
    {
        return new InsertQueryBuilder($model, $this->queryExecutor);
    }

    public function queryUpdate(ModelInterface $model): UpdateQueryBuilder
    {
        return new UpdateQueryBuilder($model, $this->queryExecutor);
    }

    public function queryDelete(ModelInterface $model): DeleteQueryBuilder
    {
        return new DeleteQueryBuilder($model, $this->queryExecutor);
    }

    public function queryTruncate(ModelInterface $model): TruncateQueryBuilder
    {
        return new TruncateQueryBuilder($model, $this->queryExecutor);
    }

    /**
     * @throws Throwable
     */
    public function transaction(callable $callback): void
    {
        try {
            $this->getConnection()->beginTransaction();
            $callback();
            $this->getConnection()->commit();
        } catch (Throwable $e) {
            $this->getConnection()->rollback();

            throw $e;
        }
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }

    /**
     * @param object|null $entity
     */
    public function save(ModelInterface $model, array $record, $entity): void
    {
        $this->persister->save($model, $record, $entity);
    }

    /**
     * @param object|null $relatedEntity
     */
    public function saveRelatedEntity(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecord,
        $relatedEntity
    ): void {
        $this->persister->saveRelatedEntity($model, $relationship, $record, $relatedRecord, $relatedEntity);
    }

    /**
     * @param object[]|null[] $relatedEntities
     */
    public function saveRelatedEntities(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecord,
        iterable $relatedEntities
    ): void {
        $this->persister->saveRelatedEntities($model, $relationship, $record, $relatedRecord, $relatedEntities);
    }

    /**
     * @param mixed $id
     */
    public function delete(ModelInterface $model, $id): void
    {
        $this->persister->delete($model, $id);
    }

    public function getIdentityMap(): IdentityMap
    {
        return $this->queryExecutor->getIdentityMap();
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
