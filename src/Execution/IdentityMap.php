<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Worm\Model\ModelInterface;
use function count;

class IdentityMap
{
    public const STATE_NEW = 0;

    public const STATE_MANAGED = 1;

    public const STATE_DELETED = 2;

    /**
     * @var array
     */
    private $identityMap = [];

    public function __construct()
    {
        $this->identityMap = [
            /*"user" => [
                "ids" => [
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
                "rels" => [
                    "addresses" => [
                        "key" => 0,
                        "type" => "address",
                    ],
                ]
            ],
            "address" => [
                "ids" => [
                    1 => [1, [], null],
                    2 => [1, [], null],
                    3 => [1, [], null],
                    4 => [1, [], null],
                ],
                "rels" => []
            ],*/
        ];
    }

    public function hasObject(string $type, string $hash): bool
    {
        return isset($this->identityMap[$type]["ids"][$hash][2]);
    }

    /**
     * @return object|null
     */
    public function getObject(string $type, string $hash)
    {
        return $this->identityMap[$type]["ids"][$hash][2] ?? null;
    }

    /**
     * @param object|null $object
     * @return object|null
     */
    public function setObject(string $type, string $hash, $object)
    {
        return $this->identityMap[$type]["ids"][$hash][2] = $object;
    }

    /**
     * @return mixed
     */
    public function createObject(ModelInterface $model, array $entity, callable $factory)
    {
        $type = $model->getTable();
        $hash = $model->getHash($entity);

        $object = $this->createObjectFromHash($type, $hash, $factory);

        $model->addRelationshipsToIdentityMap($this, $entity);

        return $object;
    }

    /**
     * @return mixed
     */
    public function createObjectFromHash(string $type, string $hash, callable $factory)
    {
        $object = $this->getObject($type, $hash);
        if ($object) {
            return $object;
        }

        $object = $factory();
        $this->addIdentity($type, $hash, $object);

        return $object;
    }

    public function hasIdentity(string $type, string $hash): bool
    {
        return isset($this->identityMap[$type]["ids"][$hash]);
    }

    public function addIdentity(string $type, string $hash, $object = null): void
    {
        if ($this->getState($type, $hash) === self::STATE_MANAGED) {
            return;
        }

        $this->identityMap[$type]["ids"][$hash] = [self::STATE_MANAGED, [], $object];
    }

    public function getState(string $type, string $hash): int
    {
        if ($this->hasIdentity($type, $hash) === false) {
            return self::STATE_NEW;
        }

        return $this->identityMap[$type]["ids"][$hash][0];
    }

    public function setState(string $type, string $hash, int $state): void
    {
        $this->identityMap[$type]["ids"][$hash][0] = $state;
    }

    public function removeIdentity(string $type, string $hash): void
    {
        unset($this->identityMap[$type]["ids"][$hash]);
    }

    public function hasRelatedIdentity(string $type, string $hash, string $relationship, string $relatedHash): bool
    {
        $relatedIds = $this->getRelatedIds($type, $hash, $relationship);

        return isset($relatedIds[$relatedHash]);
    }

    public function getRelatedIds(string $type, string $hash, string $relationship): array
    {
        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return [];
        }

        return $this->identityMap[$type]["ids"][$hash][1][$relationshipKey] ?? [];
    }

    /**
     * @var mixed $relatedId
     */
    public function addRelatedIdentity(
        string $type,
        string $hash,
        string $relationship,
        string $relatedType,
        string $relatedHash,
        $relatedId
    ): void {
        $this->addIdentity($type, $hash);

        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            $relationshipKey = $this->setRelationship($type, $relationship, $relatedType);
        }

        $this->identityMap[$type]["ids"][$hash][1][$relationshipKey][$relatedHash] = $relatedId;
    }

    public function removeRelatedIdentity(string $type, string $hash, string $relationship, string $relatedHash): void
    {
        if ($this->hasIdentity($type, $hash) === false) {
            return;
        }

        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return;
        }

        unset($this->identityMap[$type]["ids"][$hash][1][$relationshipKey][$relatedHash]);
    }

    public function getMap(): array
    {
        return $this->identityMap;
    }

    private function getRelationshipKey(string $type, string $relationship): ?int
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
