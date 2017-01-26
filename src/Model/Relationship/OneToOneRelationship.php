<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Worm\Model\ModelInterface;

class OneToOneRelationship
{
    /**
     * @var string
     */
    private $relatedModel;

    /**
     * @var string
     */
    private $foreignKey;

    /**
     * @var string
     */
    private $referencedKey;

    public function __construct(string $relatedModel, string $foreignKey, string $referencedKey)
    {
        $this->relatedModel = $relatedModel;
        $this->foreignKey = $foreignKey;
        $this->referencedKey = $referencedKey;
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
