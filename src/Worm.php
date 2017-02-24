<?php
declare(strict_types=1);

namespace WoohooLabs\Worm;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;
use WoohooLabs\Worm\Query\SelectQueryBuilder;

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

    public function queryModel(ModelInterface $model): SelectQueryBuilder
    {
        return new SelectQueryBuilder($model, $this->queryExecutor);
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
     * @param object $entity
     * @return void
     */
    public function save(ModelInterface $model, array $record, $entity)
    {
        $this->persister->save($model, $record, $entity);
    }

    /**
     * @param object $entity
     * @return void
     */
    public function saveRelatedEntities(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecord,
        $relatedEntity
    ) {
        $this->persister->saveRelatedEntities($model, $relationship, $record, $relatedRecord, $relatedEntity);
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
