<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

class HasManyThroughRelationship extends AbstractRelationship
{
    /**
     * @var string
     */
    private $referencedKey1;

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
    private $referencedKey2;

    public function __construct(
        ModelInterface $model,
        string $referencedKey1,
        ModelInterface $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        ModelInterface $referencedModel,
        string $referencedKey2
    ) {
        parent::__construct($model);
        $this->referencedKey1 = $referencedKey1;
        $this->junctionModel = $junctionModel;
        $this->foreignKey1 = $foreignKey1;
        $this->foreignKey2 = $foreignKey2;
        $this->relatedModel = $referencedModel;
        $this->referencedKey2 = $referencedKey2;
    }

    public function getQueryBuilder(
        ModelInterface $model,
        array $entities
    ): SelectQueryBuilderInterface {
        return SelectQueryBuilder::create()
            ->selectColumn("*", $this->relatedModel->getTable())
            ->selectColumn("*", $this->junctionModel->getTable())
            ->from($this->relatedModel->getTable())
            ->join($this->junctionModel->getTable())
            ->on(
                ConditionBuilder::create()
                    ->columnToColumn(
                        $this->foreignKey2,
                        "=",
                        $this->referencedKey2,
                        $this->junctionModel->getTable(),
                        $this->relatedModel->getTable()
                    )
            )
            ->join($model->getTable())
            ->on(
                ConditionBuilder::create()
                    ->columnToColumn(
                        $this->referencedKey1,
                        "=",
                        $this->foreignKey1,
                        $model->getTable(),
                        $this->junctionModel->getTable()
                    )
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
            $this->referencedKey1,
            $identityMap
        );
    }

    public function getReferencedKey1(): string
    {
        return $this->referencedKey1;
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

    public function getReferencedKey2(): string
    {
        return $this->referencedKey2;
    }
}
