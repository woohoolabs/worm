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
            ->where($this->getWhereCondition($this->junctionModel->getTable(), $this->foreignKey1, $entities));
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
    ) {
        $this->addManyToEntityMap($identityMap, $relationshipName, $parentEntity, $parentEntity[$relationshipName]);
    }
}
