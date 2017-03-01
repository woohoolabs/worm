<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use Throwable;
use Traversable;
use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\DeleteQueryBuilder;
use WoohooLabs\Worm\Query\InsertQueryBuilder;
use WoohooLabs\Worm\Query\SelectQueryBuilder;
use WoohooLabs\Worm\Query\UpdateQueryBuilder;

class Worm
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var QueryExecutor
     */
    private $queryExecutor;

    /**
     * @var Persister
     */
    private $persister;

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

    /**
     * @return void
     * @throws Throwable
     */
    public function transaction(callable $callback)
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

    /**
     * @return void
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * @return void
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * @return void
     */
    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * @param object|null $entity
     * @return void
     */
    public function save(ModelInterface $model, array $record, $entity)
    {
        $this->persister->save($model, $record, $entity);
    }

    /**
     * @param object|null $relatedEntity
     * @return void
     */
    public function saveRelatedEntity(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecord,
        $relatedEntity
    ) {
        $this->persister->saveRelatedEntity($model, $relationship, $record, $relatedRecord, $relatedEntity);
    }

    /**
     * @param array|Traversable $relatedEntities
     * @return void
     */
    public function saveRelatedEntities(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecord,
        $relatedEntities
    ) {
        $this->persister->saveRelatedEntities($model, $relationship, $record, $relatedRecord, $relatedEntities);
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function delete(ModelInterface $model, $id)
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
