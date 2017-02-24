<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
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
     * @param object $entity
     * @return void
     */
    public function save(ModelInterface $model, array $record, $entity)
    {
        $type = $model->getTable();
        $id = $model->getId($record);

        if ($this->identityMap->getState($type, $id) === IdentityMap::STATE_MANAGED) {
            $this->update($model, $record, $entity);
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
     * @param object $relatedEntity
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
        $id = $model->getId($record);

        $relatedModel = $model->getRelationship($relationship)->getModel();
        $relatedType = $relatedModel->getTable();
        $relatedId = $relatedModel->getId($relatedRecord);

        if ($this->identityMap->hasId($relatedType, $relatedId)) {
            if ($this->identityMap->hasRelatedId($type, $id, $relationship, $relatedId)) {
                $this->update($relatedModel, $relatedRecord, $relatedEntity);
            } else {
                $this->identityMap->removeRelatedId($type, $id, $relationship, $relatedId);
                $this->delete($relatedModel, $relatedId);
            }
        } else {
            $this->identityMap->addRelatedId($type, $id, $relationship, $relatedType, $relatedId);
            $this->insert($relatedModel, $relatedRecord, $relatedEntity);
        }
    }

    /**
     * @param object[] $relatedObjects
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
            $relatedId = $relatedModel->getId($relatedRecord);
            $relatedObject = $relatedObjects[$key] ?? null;

            $this->saveRelatedEntity($model, $relationship, $record, $relatedRecord, $relatedObject);
            $relatedIds[] = $relatedId;
        }

        $type = $model->getTable();
        $id = $model->getId($record);
        $deletedIds = array_diff($this->identityMap->getRelatedIds($type, $id, $relationship), $relatedIds);
        foreach ($deletedIds as $deletedId) {
            $this->delete($relatedModel, $deletedId);
        }
    }

    public function getIdentityMap(): IdentityMap
    {
        return $this->identityMap;
    }

    /**
     * @param object $entity
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
        $id = $model->getId($record);
        $this->identityMap->addId($type, $id, $entity);
    }

    /**
     * @param object $entity
     * @return void
     */
    private function update(ModelInterface $model, array $record, $entity)
    {
        $type = $model->getTable();
        $id = $model->getId($record);

        if ($this->identityMap->getState($type, $id) !== IdentityMap::STATE_MANAGED) {
            return;
        }

        $query = UpdateQueryBuilder::create()
            ->table($model->getTable())
            ->setValues($record)
            ->where(
                ConditionBuilder::create()
                    ->columnToValue($model->getPrimaryKey(), "=", $model->getId($record))
            );

        $query->execute($this->connection);

        $this->identityMap->setObject($type, $id, $entity);
    }

    /**
     * @param mixed $id
     * @return void
     */
    private function doDelete(ModelInterface $model, $id)
    {
        $type = $model->getTable();

        if ($this->identityMap->getState($type, $id) !== IdentityMap::STATE_MANAGED) {
            return;
        }

        $query = DeleteQueryBuilder::create()
            ->from($model->getTable())
            ->where(
                ConditionBuilder::create()
                    ->columnToValue($model->getPrimaryKey(), "=", $id)
            );

        $query->execute($this->connection);

        $this->identityMap->setState($type, $id, IdentityMap::STATE_DELETED);
        $this->identityMap->setObject($type, $id, null);
        $model->cascadeDelete($this, $id);
    }
}
