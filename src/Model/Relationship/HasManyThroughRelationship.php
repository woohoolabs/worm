<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

class HasManyThroughRelationship extends AbstractRelationship
{
    private string $referencedKey1;
    private ModelInterface $junctionModel;
    private string $foreignKey1;
    private string $foreignKey2;
    private ModelInterface $relatedModel;
    private string $referencedKey2;

    public function __construct(
        ModelInterface $parentModel,
        string $referencedKey1,
        ModelInterface $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        ModelInterface $relatedModel,
        string $referencedKey2,
        bool $isCascadedDelete = false
    ) {
        parent::__construct($parentModel, $isCascadedDelete);
        $this->referencedKey1 = $referencedKey1;
        $this->junctionModel = $junctionModel;
        $this->foreignKey1 = $foreignKey1;
        $this->foreignKey2 = $foreignKey2;
        $this->relatedModel = $relatedModel;
        $this->referencedKey2 = $referencedKey2;
    }

    public function getModel(): ModelInterface
    {
        return $this->relatedModel;
    }

    public function getQueryBuilder(array $entities): SelectQueryBuilderInterface
    {
        $queryBuilder = clone $this->queryBuilder;

        return $queryBuilder
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
            ->addWhereGroup(
                $this->getWhereCondition(
                    $entities,
                    $this->referencedKey1,
                    $this->junctionModel->getTable(),
                    $this->foreignKey1
                )
            );
    }

    public function connectToParent(SelectQueryBuilderInterface $selectQueryBuilder): void
    {
        $selectQueryBuilder
            ->join($this->junctionModel->getTable())
            ->on(
                ConditionBuilder::create()
                    ->columnToColumn(
                        $this->foreignKey1,
                        "=",
                        $this->referencedKey1,
                        $this->junctionModel->getTable(),
                        $this->parentModel->getTable()
                    )
                    ->columnToColumn(
                        $this->foreignKey2,
                        "=",
                        $this->referencedKey2,
                        $this->junctionModel->getTable(),
                        $this->relatedModel->getTable()
                    )
            );
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
            $relatedEntities,
            $this->foreignKey1,
            $this->referencedKey1,
            $identityMap
        );
    }

    public function addRelationshipToIdentityMap(
        IdentityMap $identityMap,
        string $relationshipName,
        array $parentEntity
    ): void {
        $this->addManyToEntityMap($identityMap, $relationshipName, $parentEntity, $parentEntity[$relationshipName]);
    }
}
