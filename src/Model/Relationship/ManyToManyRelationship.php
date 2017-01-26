<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

class ManyToManyRelationship
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
    private $referencedModel;

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
        $this->referencedModel = $referencedModel;
        $this->referencedKey = $referencedKey;
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

    public function getReferencedModel(): string
    {
        return $this->referencedModel;
    }

    public function getReferencedKey(): string
    {
        return $this->referencedKey;
    }
}
