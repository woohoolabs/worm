<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Model\Relationship;

use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;

interface RelationshipBuilderInterface
{
    public function distinct(bool $isDistinct = true): RelationshipBuilderInterface;

    public function where(ConditionBuilderInterface $where): RelationshipBuilderInterface;

    public function addWhereGroup(ConditionBuilderInterface $where, string $operator = "AND"): RelationshipBuilderInterface;

    public function groupBy(string $attribute): RelationshipBuilderInterface;

    public function groupByAttributes(array $attributes): RelationshipBuilderInterface;

    public function having(ConditionBuilderInterface $having): RelationshipBuilderInterface;

    public function orderBy(string $attribute, string $direction = "ASC"): RelationshipBuilderInterface;

    public function limit(?int $limit): RelationshipBuilderInterface;

    public function offset(?int $offset): RelationshipBuilderInterface;

    public function toRelationship(): RelationshipInterface;
}
