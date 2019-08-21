<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

use DomainException;
use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Execution\Persister;
use WoohooLabs\Worm\Model\Relationship\BelongsToManyRelationship;
use WoohooLabs\Worm\Model\Relationship\BelongsToOneRelationship;
use WoohooLabs\Worm\Model\Relationship\HasManyRelationship;
use WoohooLabs\Worm\Model\Relationship\HasManyThroughRelationship;
use WoohooLabs\Worm\Model\Relationship\HasOneRelationship;
use WoohooLabs\Worm\Model\Relationship\RelationshipBuilderInterface;
use WoohooLabs\Worm\Model\Relationship\RelationshipInterface;
use function array_keys;
use function count;
use function get_object_vars;
use function is_array;
use function is_callable;
use function reset;

abstract class AbstractModel implements ModelInterface
{
    private array $relationships = [];

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

        $this->relationships = $this->getRelationships();
    }

    /**
     * @return string[]
     */
    public function getRelationshipNames(): array
    {
        return array_keys($this->relationships);
    }

    public function getRelationship(string $name): RelationshipInterface
    {
        if (array_key_exists($name, $this->relationships) === false) {
            throw new DomainException("Relationship '$name' does not exist!");
        }

        if (is_callable($this->relationships[$name])) {
            $this->relationships[$name] = $this->relationships[$name]();
        }

        return $this->relationships[$name];
    }

    /**
     * @return mixed|mixed[]|null
     */
    public function getId(array $record)
    {
        $primaryKeys = $this->getPrimaryKeys();

        // Simple primary key
        if (count($primaryKeys) === 1) {
            return $record[$primaryKeys[0]] ?? null;
        }

        // Composite primary key
        $id = [];
        foreach ($primaryKeys as $primaryKey) {
            if (array_key_exists($primaryKey, $record) === false) {
                return null;
            }

            $id[$primaryKey] = $record[$primaryKey];
        }

        return $id;
    }

    public function getHash(array $record): string
    {
        $id = "";
        foreach ($this->getPrimaryKeys() as $primaryKey) {
            $id .= (string) ($record[$primaryKey] ?? "") . ".";
        }

        return $id;
    }

    /**
     * @param mixed $id
     */
    public function getHashFromId($id): string
    {
        if (is_array($id)) {
            return $this->getHash($id);
        }

        return $id . ".";
    }

    /**
     * @param mixed $id
     */
    public function createConditionBuilder($id): ConditionBuilderInterface
    {
        $primaryKeys = $this->getPrimaryKeys();

        if (is_array($id) === false) {
            $id = [reset($primaryKeys) => $id];
        }

        $conditionBuilder = ConditionBuilder::create();
        foreach ($primaryKeys as $k => $primaryKey) {
            if ($k !== 0) {
                $conditionBuilder->and();
            }
            $conditionBuilder->columnToValue($primaryKey, "=", $id[$primaryKey] ?? null);
        }

        return $conditionBuilder;
    }

    public function addRelationshipsToIdentityMap(IdentityMap $identityMap, array $entity): void
    {
        $relationshipNames = $this->getRelationshipNames();
        foreach ($relationshipNames as $relationshipName) {
            if (array_key_exists($relationshipName, $entity) === false) {
                continue;
            }

            $relationship = $this->getRelationship($relationshipName);
            $relationship->addRelationshipToIdentityMap($identityMap, $relationshipName, $entity);
        }
    }

    /**
     * @param mixed $id
     */
    public function cascadeDelete(Persister $persister, $id): void
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
    ): RelationshipBuilderInterface {
        return new BelongsToOneRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function belongsToMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): RelationshipBuilderInterface {
        return new BelongsToManyRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function hasOne(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): RelationshipBuilderInterface {
        return new HasOneRelationship($this, $relatedModel, $foreignKey, $referencedKey, $isCascadedDelete);
    }

    protected function hasMany(
        ModelInterface $relatedModel,
        string $foreignKey,
        string $referencedKey,
        bool $isCascadedDelete = false
    ): RelationshipBuilderInterface {
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
    ): RelationshipBuilderInterface {
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
