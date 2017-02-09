<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

class IdentityMap
{
    /**
     * @var object[]
     */
    private $identityMap = [];

    public function __construct()
    {
        $this->identityMap = [
            /*"user" => [
                [
                    1 => [
                        null,
                        [
                            0 => [
                                1,
                                2
                            ],
                        ]
                    ],
                    2 => [
                        null,
                        [
                            0 => [
                                3,
                                4
                            ],
                        ]
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
                    1 => [null, []],
                    2 => [null, []],
                    3 => [null, []],
                    4 => [null, []],
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
        return isset($this->identityMap[$type]["ids"][$id][0]);
    }

    /**
     * @param mixed $id
     * @return object
     */
    public function getObject(string $type, $id)
    {
        return $this->identityMap[$type]["ids"][$id][0] ?? null;
    }

    /**
     * @param mixed $id
     * @param callable $factory
     * @return object
     */
    public function createObject(string $type, $id, callable $factory)
    {
        $object = $this->getObject($type, $id);
        if ($object) {
            return $object;
        }

        $object = $factory();
        $this->setObject($type, $id, $object);

        return $object;
    }

    /**
     * @param mixed $id
     */
    public function hasId(string $type, $id): bool
    {
        return isset($this->identityMap[$type]["ids"][$id]);
    }

    public function addId(string $type, $id)
    {
        if ($this->hasId($type, $id)) {
            return;
        }

        $this->identityMap[$type]["ids"][$id] = [null, []];
    }

    public function hasRelatedId(string $type, $id, string $relationship, $relatedId): bool
    {
        $relatedIds = $this->getRelatedIds($type, $id, $relationship);

        return isset($relatedIds[$relatedId]);
    }

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

    public function getMap(): array
    {
        return $this->identityMap;
    }

    /**
     * @param mixed $id
     * @param object $object
     */
    private function setObject(string $type, $id, $object)
    {
        $this->identityMap[$type]["ids"][$id][0] = $object;
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

    private function getRelatedIds(string $type, $id, string $relationship): array
    {
        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return [];
        }

        return $this->identityMap[$type]["ids"][$id][1][$relationshipKey] ?? [];
    }
}
