<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilder;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

class BelongsToOneRelationship extends AbstractRelationship
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
        ModelInterface $parentModel,
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ) {
        parent::__construct($parentModel, $isCascadedDelete);
        $this->relatedModel = $relatedModel;
        $this->foreignKey = $foreignKey;
        $this->referencedKey = $referencedKey;
    }

    public function getModel(): ModelInterface
    {
        return $this->relatedModel;
    }

    public function getQueryBuilder(array $entities): SelectQueryBuilderInterface
    {
        return SelectQueryBuilder::create()
            ->selectColumn("*", $this->relatedModel->getTable())
            ->from($this->relatedModel->getTable())
            ->join($this->parentModel->getTable())
            ->on(
                ConditionBuilder::create()
                    ->columnToColumn(
                        $this->foreignKey,
                        "=",
                        $this->referencedKey,
                        $this->parentModel->getTable(),
                        $this->relatedModel->getTable()
                    )
            )
            ->where(
                $this->getWhereCondition(
                    $entities,
                    $this->foreignKey,
                    $this->relatedModel->getTable(),
                    $this->referencedKey
                )
            );
    }

    public function connectToParent(SelectQueryBuilderInterface $selectQueryBuilder)
    {
        $selectQueryBuilder
            ->addWhereGroup(
                ConditionBuilder::create()
                    ->columnToColumn(
                        $this->foreignKey,
                        "=",
                        $this->referencedKey,
                        $this->relatedModel->getTable(),
                        $this->parentModel->getTable()
                    )
            );
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
            $relatedEntities,
            $this->referencedKey,
            $this->foreignKey,
            $identityMap
        );
    }

    public function addRelationshipToIdentityMap(
        IdentityMap $identityMap,
        string $relationshipName,
        array $parentEntity
    ) {
        $this->addOneToEntityMap($identityMap, $relationshipName, $parentEntity, $parentEntity[$relationshipName]);
    }
}
