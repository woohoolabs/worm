<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Delete\DeleteQueryBuilder;
use WoohooLabs\Larva\Query\Insert\InsertQueryBuilder;
use WoohooLabs\Larva\Query\Update\UpdateQueryBuilder;
use WoohooLabs\Worm\Model\ModelInterface;

class Persister
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

    /**
     * @param object|null $entity
     * @return void
     */
    public function save(ModelInterface $model, array $record, $entity)
    {
        $type = $model->getTable();
        $hash = $model->getHash($record);

        if ($this->identityMap->getState($type, $hash) === IdentityMap::STATE_MANAGED) {
            $this->update($model, $record, $hash, $entity);
        } else {
            $this->insert($model, $record, $entity);
        }
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function delete(ModelInterface $model, $id)
    {
        $this->doDelete($model, $id);
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
        $type = $model->getTable();
        $hash = $model->getHash($record);

        $relatedModel = $model->getRelationship($relationship)->getModel();
        $relatedType = $relatedModel->getTable();
        $relatedHash = $relatedModel->getHash($relatedRecord);
        $relatedId = $relatedModel->getId($relatedRecord);

        if ($this->identityMap->hasIdentity($relatedType, $relatedHash)) {
            if ($this->identityMap->hasRelatedIdentity($type, $hash, $relationship, $relatedHash)) {
                $this->update($relatedModel, $relatedRecord, $relatedHash, $relatedEntity);
            } else {
                $this->identityMap->removeRelatedIdentity($type, $hash, $relationship, $relatedHash);
                $relatedId = $relatedModel->getId($relatedRecord);
                $this->delete($relatedModel, $relatedId);
            }
        } else {
            $this->identityMap->addRelatedIdentity($type, $hash, $relationship, $relatedType, $relatedHash, $relatedId);
            $this->insert($relatedModel, $relatedRecord, $relatedEntity);
        }
    }

    /**
     * @param object[]|null[] $relatedObjects
     * @return void
     */
    public function saveRelatedEntities(
        ModelInterface $model,
        string $relationship,
        array $record,
        array $relatedRecords,
        $relatedObjects
    ) {
        $relatedModel = $model->getRelationship($relationship)->getModel();

        $relatedIds = [];
        foreach ($relatedRecords as $key => $relatedRecord) {
            $relatedObject = $relatedObjects[$key] ?? null;

            $this->saveRelatedEntity($model, $relationship, $record, $relatedRecord, $relatedObject);
            $relatedIds[] = $relatedModel->getId($relatedRecord);
        }

        $type = $model->getTable();
        $hash = $model->getHash($record);
        $deletedIds = array_diff($this->identityMap->getRelatedIds($type, $hash, $relationship), $relatedIds);
        foreach ($deletedIds as $deletedId) {
            $this->delete($relatedModel, $deletedId);
        }
    }

    public function getIdentityMap(): IdentityMap
    {
        return $this->identityMap;
    }

    /**
     * @param object|null $entity
     * @return void
     */
    private function insert(ModelInterface $model, array $record, $entity)
    {
        $query = InsertQueryBuilder::create()
            ->into($model->getTable())
            ->columns(array_keys($record))
            ->values(array_values($record));

        $query->execute($this->connection);

        $type = $model->getTable();
        $hash = $model->getHash($record);
        $this->identityMap->addIdentity($type, $hash, $entity);
    }

    /**
     * @param object|null $entity
     * @return void
     */
    private function update(ModelInterface $model, array $record, string $hash, $entity)
    {
        $type = $model->getTable();
        $id = $model->getId($record);

        if ($this->identityMap->getState($type, $hash) !== IdentityMap::STATE_MANAGED) {
            return;
        }

        $query = UpdateQueryBuilder::create()
            ->table($model->getTable())
            ->setValues($record)
            ->where($model->createConditionBuilder($id));

        $query->execute($this->connection);

        $this->identityMap->setObject($type, $hash, $entity);
    }

    /**
     * @param mixed $id
     * @return void
     */
    private function doDelete(ModelInterface $model, $id)
    {
        $type = $model->getTable();
        $hash = $model->getHashFromId($id);

        if ($this->identityMap->getState($type, $hash) !== IdentityMap::STATE_MANAGED) {
            return;
        }

        $query = DeleteQueryBuilder::create()
            ->from($model->getTable())
            ->where($model->createConditionBuilder($id));

        $query->execute($this->connection);

        $this->identityMap->setState($type, $hash, IdentityMap::STATE_DELETED);
        $this->identityMap->setObject($type, $hash, null);
        $model->cascadeDelete($this, $id);
    }
}
