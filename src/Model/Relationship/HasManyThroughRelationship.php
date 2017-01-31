<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\ModelContainer;
use WoohooLabs\Worm\Model\ModelInterface;

class HasManyThroughRelationship extends AbstractRelationship
{
    /**
     * @var string
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
     * @var string
     */
    private $relatedModel;

    /**
     * @var string
     */
    private $referencedKey;

    public function __construct(
        string $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        string $referencedModel,
        string $referencedKey
    ) {
        $this->junctionModel = $junctionModel;
        $this->foreignKey1 = $foreignKey1;
        $this->foreignKey2 = $foreignKey2;
        $this->relatedModel = $referencedModel;
        $this->referencedKey = $referencedKey;
    }

    public function getRelationship(
        ModelInterface $model,
        ModelContainer $container,
        ConnectionInterface $connection,
        array $entities
    ): SelectQueryBuilderInterface {
        $junctionModel = $container->get($this->junctionModel);
        $relatedModel = $container->get($this->relatedModel);

        return SelectQueryBuilder::create($connection)
            ->selectColumn("*", $relatedModel->getTable())
            ->selectColumn("*", $junctionModel->getTable())
            ->from($relatedModel->getTable())
            ->join($junctionModel->getTable())
            ->on(
                function (ConditionBuilderInterface $on) use ($junctionModel, $relatedModel) {
                    $on->columnToColumn(
                        $this->foreignKey2,
                        "=",
                        $this->referencedKey,
                        $junctionModel->getTable(),
                        $relatedModel->getTable()
                    );
                }
            )
            ->join($model->getTable())
            ->on(
                function (ConditionBuilderInterface $on) use ($model, $junctionModel) {
                    $on->columnToColumn(
                        $model->getPrimaryKey(),
                        "=",
                        $this->foreignKey1,
                        $model->getTable(),
                        $junctionModel->getTable()
                    );
                }
            )
            ->where($this->getWhereCondition($model, $entities));
    }

    public function matchRelationship(array $entities, string $relationshipName, array $relatedEntities): array
    {
        $relatedEntities = $this->getEntityMapForMany($relatedEntities, $this->foreignKey1);

        return $this->insertRelationship($entities, $relationshipName, $relatedEntities, "id");
    }

    public function getJunctionModel(): string
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

    public function getRelatedModel(): string
    {
        return $this->relatedModel;
    }

    public function getReferencedKey(): string
    {
        return $this->referencedKey;
    }
}
