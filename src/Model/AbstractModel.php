<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use DomainException;
use WoohooLabs\Worm\Model\Relationship\BelongsToManyRelationship;
use WoohooLabs\Worm\Model\Relationship\BelongsToOneRelationship;
use WoohooLabs\Worm\Model\Relationship\HasManyRelationship;
use WoohooLabs\Worm\Model\Relationship\HasManyThroughRelationship;
use WoohooLabs\Worm\Model\Relationship\HasOneRelationship;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @var array
     */
    private $relationships = [];

    /**
     * @return callable[]
     */
    abstract protected function getRelationships(): array;

    public function __construct()
    {
        $variables = get_object_vars($this);
        foreach ($variables as $variable => $value) {
            if ($value !== null) {
                continue;
            }

            $this->$variable = $variable;
        }
    }

    public function getRelationshipNames(): array
    {
        if (empty($this->relationships)) {
            $this->relationships = $this->getRelationships();
        }

        return array_keys($this->relationships);
    }

    public function getRelationship(string $name): RelationshipInterface
    {
        if (isset($this->relationships[$name]) === false) {
            throw new DomainException("Relationhip '$name' does not exist!");
        }

        if (is_callable($this->relationships[$name])) {
            $this->relationships[$name] = $this->relationships[$name]();
        }

        return $this->relationships[$name];
    }

    protected function belongsToOne(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey
    ): BelongsToOneRelationship {
        return new BelongsToOneRelationship($this, $relatedModel, $foreignKey, $referencedKey);
    }

    protected function belongsToMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey
    ): BelongsToManyRelationship {
        return new BelongsToManyRelationship($this, $relatedModel, $foreignKey, $referencedKey);
    }

    protected function hasOne(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey
    ): HasOneRelationship {
        return new HasOneRelationship($this, $relatedModel, $foreignKey, $referencedKey);
    }

    protected function hasMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey
    ): HasManyRelationship {
        return new HasManyRelationship($this, $relatedModel, $foreignKey, $referencedKey);
    }

    protected function hasManyThrough(
        string $referencedKey1,
        ModelInterface $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        ModelInterface $referencedModel,
        string $referencedKey2
    ): HasManyThroughRelationship {
        return new HasManyThroughRelationship(
            $this,
            $referencedKey1,
            $junctionModel,
            $foreignKey1,
            $foreignKey2,
            $referencedModel,
            $referencedKey2
        );
    }
}
