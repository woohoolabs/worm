<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Connection\ConnectionInterface;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

class HasOneRelationship extends AbstractRelationship
{
    /**
     * @var ModelInterface
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

    public function __construct(
        ModelInterface $model,
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey
    ) {
        parent::__construct($model);
        $this->relatedModel = $relatedModel;
        $this->foreignKey = $foreignKey;
        $this->referencedKey = $referencedKey;
    }

    public function getQueryBuilder(
        ModelInterface $model,
        ConnectionInterface $connection,
        array $entities
    ): SelectQueryBuilderInterface {
        return SelectQueryBuilder::create($connection)
            ->selectColumn("*", $this->relatedModel->getTable())
            ->from($this->relatedModel->getTable())
            ->join($model->getTable())
            ->on(
                function (ConditionBuilderInterface $on) use ($model) {
                    $on->columnToColumn(
                        $this->referencedKey,
                        "=",
                        $this->foreignKey,
                        $model->getTable(),
                        $this->relatedModel->getTable()
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
        return $this->insertOneRelationship(
            $entities,
            $relationshipName,
            $this->relatedModel,
            $relatedEntities,
            $this->foreignKey,
            $this->referencedKey,
            $identityMap
        );
    }

    public function getRelatedModel(): ModelInterface
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
