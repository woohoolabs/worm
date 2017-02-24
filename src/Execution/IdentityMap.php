<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Worm\Model\ModelInterface;

class IdentityMap
{
    const STATE_NEW = 0;

    const STATE_MANAGED = 1;

    const STATE_DELETED = 2;

    /**
     * @var array
     */
    private $identityMap = [];

    public function __construct()
    {
        $this->identityMap = [
            /*"user" => [
                [
                    1 => [
                        1,
                        [
                            0 => [
                                1,
                                2
                            ],
                        ],
                        null,
                    ],
                    2 => [
                        1,
                        [
                            0 => [
                                3,
                                4
                            ],
                        ],
                        null,
                    ],
                ],
                [
                    "addresses" => [
                        "key" => 0,
                        "type" => "address",
                    ],
                ]
            ],
            "address" => [
                [
                    1 => [1, [], null],
                    2 => [1, [], null],
                    3 => [1, [], null],
                    4 => [1, [], null],
                ],
                []
            ],*/
        ];
    }

    /**
     * @param mixed $id
     */
    public function hasObject(string $type, $id): bool
    {
        return isset($this->identityMap[$type]["ids"][$id][2]);
    }

    /**
     * @param mixed $id
     * @return object|null
     */
    public function getObject(string $type, $id)
    {
        return $this->identityMap[$type]["ids"][$id][2] ?? null;
    }

    /**
     * @param mixed $id
     * @param object|null $object
     * @return object|null
     */
    public function setObject(string $type, $id, $object)
    {
        return $this->identityMap[$type]["ids"][$id][2] = $object;
    }

    /**
     * @return object
     */
    public function createObject(ModelInterface $model, array $entity, callable $factory)
    {
        $type = $model->getTable();
        $id = $model->getId($entity);

        $object = $this->getObject($type, $id);
        if ($object) {
            return $object;
        }

        $object = $factory($entity);
        $this->addId($type, $id, $object);
        $model->addRelationshipsToIdentityMap($this, $entity);

        return $object;
    }

    /**
     * @param mixed $id
     */
    public function hasId(string $type, $id): bool
    {
        return isset($this->identityMap[$type]["ids"][$id]);
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function addId(string $type, $id, $object = null)
    {
        if ($this->getState($type, $id) === self::STATE_MANAGED) {
            return;
        }

        $this->identityMap[$type]["ids"][$id] = [self::STATE_MANAGED, [], $object];
    }

    /**
     * @param mixed $id
     */
    public function getState(string $type, $id): int
    {
        if ($this->hasId($type, $id) === false) {
            return self::STATE_NEW;
        }

        return $this->identityMap[$type]["ids"][$id][0];
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function setState(string $type, $id, int $state)
    {
        $this->identityMap[$type]["ids"][$id][0] = $state;
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function removeId(string $type, $id)
    {
        unset($this->identityMap[$type]["ids"][$id]);
    }

    /**
     * @param mixed $id
     */
    public function hasRelatedId(string $type, $id, string $relationship, $relatedId): bool
    {
        $relatedIds = $this->getRelatedIds($type, $id, $relationship);

        return isset($relatedIds[$relatedId]);
    }

    /**
     * @param mixed $id
     */
    public function getRelatedIds(string $type, $id, string $relationship): array
    {
        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return [];
        }

        return $this->identityMap[$type]["ids"][$id][1][$relationshipKey] ?? [];
    }

    /**
     * @param mixed $id
     * @param mixed[] $relatedIds
     * @return void
     */
    public function setRelatedIds(string $type, $id, string $relationship, string $relatedType, array $relatedIds)
    {
        if ($this->hasId($type, $id) === false) {
            return;
        }

        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            $relationshipKey = $this->setRelationship($type, $relationship, $relatedType);
        }

        $this->identityMap[$type]["ids"][$id][1][$relationshipKey] = array_flip($relatedIds);
    }

    /**
     * @param mixed $id
     * @param mixed $relatedId
     * @return void
     */
    public function addRelatedId(string $type, $id, string $relationship, string $relatedType, $relatedId)
    {
        if ($this->hasId($type, $id) === false) {
            return;
        }

        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            $relationshipKey = $this->setRelationship($type, $relationship, $relatedType);
        }

        $this->identityMap[$type]["ids"][$id][1][$relationshipKey][$relatedId] = $relatedId;
    }

    /**
     * @param mixed $id
     * @param mixed $relatedId
     * @return void
     */
    public function removeRelatedId(string $type, $id, string $relationship, $relatedId)
    {
        if ($this->hasId($type, $id) === false) {
            return;
        }

        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return;
        }

        unset($this->identityMap[$type]["ids"][$id][1][$relationshipKey][$relatedId]);
    }

    public function getMap(): array
    {
        return $this->identityMap;
    }

    /**
     * @return mixed|null
     */
    private function getRelationshipKey(string $type, string $relationship)
    {
        return $this->identityMap[$type]["rels"][$relationship]["key"] ?? null;
    }

    private function setRelationship(string $type, string $relationship, string $relatedType): int
    {
        $key = count($this->identityMap[$type]["rels"][$relationship] ?? []);

        $this->identityMap[$type]["rels"][$relationship] = [
            "key" => $key,
            "type" => $relatedType,
        ];

        return $key;
    }
}
