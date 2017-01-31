<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\ModelContainer;
use WoohooLabs\Worm\Model\ModelInterface;

class BelongsToOneRelationship extends AbstractRelationship
{
    /**
     * @var string
     */
    protected $relatedModel;

    /**
     * @var string
     */
    protected $foreignKey;

    /**
     * @var string
     */
    protected $referencedKey;

    public function __construct(string $relatedModel, string $foreignKey, string $referencedKey)
    {
        $this->relatedModel = $relatedModel;
        $this->foreignKey = $foreignKey;
        $this->referencedKey = $referencedKey;
    }

    public function getRelationship(
        ModelInterface $model,
        ModelContainer $container,
        ConnectionInterface $connection,
        array $entities
    ): SelectQueryBuilderInterface {
        $relatedModel = $container->get($this->relatedModel);

        return SelectQueryBuilder::create($connection)
            ->selectColumn("*", $relatedModel->getTable())
            ->from($relatedModel->getTable())
            ->join($model->getTable())
            ->on(
                function (ConditionBuilderInterface $on) use ($model, $relatedModel) {
                    $on->columnToColumn(
                        $this->foreignKey,
                        "=",
                        $this->referencedKey,
                        $model->getTable(),
                        $relatedModel->getTable()
                    );
                }
            )
            ->where($this->getWhereCondition($model, $entities));
    }

    public function matchRelationship(array $entities, string $relationshipName, array $relatedEntities): array
    {
        $relatedEntities = $this->getEntityMapForOne($relatedEntities, $this->referencedKey);

        return $this->insertRelationship($entities, $relationshipName, $relatedEntities, $this->foreignKey);
    }

    public function getRelatedModel(): string
    {
        return $this->relatedModel;
    }

    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    public function getReferencedKey(): string
    {
        return $this->referencedKey;
    }
}
