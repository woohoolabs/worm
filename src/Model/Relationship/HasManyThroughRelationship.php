<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

class HasManyThroughRelationship extends AbstractRelationship
{
    /**
     * @var ModelInterface
     */
    private $junctionModel;

    /**
     * @var string
     */
    private $foreignKey1;

    /**
     * @var string
     */
    private $foreignKey2;

    /**
     * @var ModelInterface
     */
    private $relatedModel;

    /**
     * @var string
     */
    private $referencedKey;

    public function __construct(
        ModelInterface $model,
        ModelInterface $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        ModelInterface $referencedModel,
        string $referencedKey
    ) {
        parent::__construct($model);
        $this->junctionModel = $junctionModel;
        $this->foreignKey1 = $foreignKey1;
        $this->foreignKey2 = $foreignKey2;
        $this->relatedModel = $referencedModel;
        $this->referencedKey = $referencedKey;
    }

    public function getRelationship(
        ModelInterface $model,
        ConnectionInterface $connection,
        array $entities
    ): SelectQueryBuilderInterface {
        return SelectQueryBuilder::create($connection)
            ->selectColumn("*", $this->relatedModel->getTable())
            ->selectColumn("*", $this->junctionModel->getTable())
            ->from($this->relatedModel->getTable())
            ->join($this->junctionModel->getTable())
            ->on(
                function (ConditionBuilderInterface $on) {
                    $on->columnToColumn(
                        $this->foreignKey2,
                        "=",
                        $this->referencedKey,
                        $this->junctionModel->getTable(),
                        $this->relatedModel->getTable()
                    );
                }
            )
            ->join($model->getTable())
            ->on(
                function (ConditionBuilderInterface $on) use ($model) {
                    $on->columnToColumn(
                        $model->getPrimaryKey(),
                        "=",
                        $this->foreignKey1,
                        $model->getTable(),
                        $this->junctionModel->getTable()
                    );
                }
            )
            ->where($this->getWhereCondition($model, $entities));
    }

    public function matchRelationship(
        array $entities,
        string $relationshipName,
        array $relatedEntities,
        IdentityMap $identityMap
    ): array {
        return $this->insertManyRelationship(
            $entities,
            $relationshipName,
            $this->relatedModel,
            $relatedEntities,
            $this->foreignKey1,
            "id",
            $identityMap
        );
    }

    public function getJunctionModel(): ModelInterface
    {
        return $this->junctionModel;
    }

    public function getForeignKey1(): string
    {
        return $this->foreignKey1;
    }

    public function getForeignKey2(): string
    {
        return $this->foreignKey2;
    }

    public function getRelatedModel(): ModelInterface
    {
        return $this->relatedModel;
    }

    public function getReferencedKey(): string
    {
        return $this->referencedKey;
    }
}
