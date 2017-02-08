<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use LogicException;

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
     * @param mixed $record
     * @param callable $factory
     * @return object
     * @throws LogicException
     */
    public function createObject(string $type, string $idField, $record, callable $factory)
    {
        if (isset($record[$idField]) === false) {
            throw new LogicException("The record doesn't have an ID and can't be hydrated to an object!");
        }

        $id = $record[$idField];

        $object = $this->getObject($type, $id);
        if ($object) {
            return $object;
        }

        $object = $factory();
        $this->setObject($type, $id, $object);

        return $object;
    }

    public function addId(string $type, $id)
    {
        if ($this->hasId($type, $id)) {
            return;
        }

        $this->identityMap[$type]["ids"][$id] = [null, []];
    }

    public function getRelatedIds(string $type, $id, string $relationship): array
    {
        $relationshipKey = $this->getRelationshipKey($type, $relationship);
        if ($relationshipKey === null) {
            return [];
        }

        return $this->identityMap[$type]["ids"][$id][1][$relationshipKey] ?? [];
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

        $this->identityMap[$type]["ids"][$id][1][$relationshipKey][] = $relatedId;
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
     * @param mixed $id
     */
    private function hasId(string $type, $id): bool
    {
        return isset($this->identityMap[$type]["ids"][$id]);
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
