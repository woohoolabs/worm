<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Larva\Query\Truncate\TruncateQueryBuilder as LarvaTruncateQueryBuilder;
use WoohooLabs\Larva\Query\Truncate\TruncateQueryBuilderInterface;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;

class TruncateQueryBuilder
{
    private QueryExecutor $queryExecutor;
    private LarvaTruncateQueryBuilder $queryBuilder;

    public function __construct(ModelInterface $model, QueryExecutor $executor)
    {
        $this->queryExecutor = $executor;
        $this->queryBuilder = new LarvaTruncateQueryBuilder();
        $this->queryBuilder->table($model->getTable());
    }

    public function getQueryBuilder(): TruncateQueryBuilderInterface
    {
        return $this->queryBuilder;
    }

    public function execute(): bool
    {
        return $this->queryExecutor->truncate($this->queryBuilder);
    }

    public function getSql(): string
    {
        return $this->queryExecutor->getSql($this->queryBuilder);
    }

    public function getParams(): array
    {
        return $this->queryExecutor->getParams($this->queryBuilder);
    }

    public function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
