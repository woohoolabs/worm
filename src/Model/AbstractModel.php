<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use DomainException;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
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

    /**
     * @return string[]
     */
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
            throw new DomainException("Relationship '$name' does not exist!");
        }

        if (is_callable($this->relationships[$name])) {
            $this->relationships[$name] = $this->relationships[$name]();
        }

        return $this->relationships[$name];
    }

    /**
     * @return mixed|null
     */
    public function getId(array $record)
    {
        return $record[$this->getPrimaryKey()] ?? null;
    }

    public function addRelationshipsToIdentityMap(IdentityMap $identityMap, array $entity)
    {
        $relationshipNames = $this->getRelationshipNames();
        foreach ($relationshipNames as $relationshipName) {
            if (empty($record[$relationshipName])) {
                continue;
            }

            $relationship = $this->getRelationship($relationshipName);
            $relationship->addRelationshipToIdentityMap($identityMap, $relationshipName, $entity);
        }
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function cascadeDelete(Persister $persister, $id)
    {
        foreach ($this->getRelationshipNames() as $relationshipName) {
            $relationship = $this->getRelationship($relationshipName);
            $relationship->cascadeDelete($persister, $relationshipName, $id);
        }
    }

    protected function belongsToOne(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): BelongsToOneRelationship {
        return new BelongsToOneRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function belongsToMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): BelongsToManyRelationship {
        return new BelongsToManyRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function hasOne(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): HasOneRelationship {
        return new HasOneRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function hasMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): HasManyRelationship {
        return new HasManyRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function hasManyThrough(
        string $referencedKey1,
        ModelInterface $junctionModel,
        string $foreignKey1,
        string $foreignKey2,
        ModelInterface $referencedModel,
        string $referencedKey2,
        bool $isCascadedDelete = false
    ): HasManyThroughRelationship {
        return new HasManyThroughRelationship(
            $this,
            $referencedKey1,
            $junctionModel,
            $foreignKey1,
            $foreignKey2,
            $referencedModel,
            $referencedKey2,
            $isCascadedDelete
        );
    }
}
