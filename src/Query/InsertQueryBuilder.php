<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Larva\Query\Insert\InsertQueryBuilder as LarvaInsertQueryBuilder;
use WoohooLabs\Larva\Query\Insert\InsertQueryBuilderInterface;
use WoohooLabs\Larva\Query\Select\SelectQueryBuilderInterface;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;
use function array_key_exists;
use function array_keys;
use function array_values;

class InsertQueryBuilder
{
    private QueryExecutor $queryExecutor;
    private LarvaInsertQueryBuilder $queryBuilder;

    public function __construct(ModelInterface $model, QueryExecutor $executor)
    {
        $this->queryExecutor = $executor;
        $this->queryBuilder = new LarvaInsertQueryBuilder();
        $this->queryBuilder->into($model->getTable());
    }

    public function fields(array $fields): InsertQueryBuilder
    {
        $columns = array_keys($fields);
        $values = array_values($fields);

        $this->queryBuilder->columns($columns);
        $this->queryBuilder->values($values);

        return $this;
    }

    public function multipleFields(array $fields): InsertQueryBuilder
    {
        if ($fields  === [] || array_key_exists(0, $fields) === false) {
            return $this;
        }

        $columns = array_keys($fields[0]);

        $values = [];
        foreach ($fields as $record) {
            $values[] = array_values($record);
        }

        $this->queryBuilder->columns($columns);
        $this->queryBuilder->multipleValues($values);

        return $this;
    }

    public function select(SelectQueryBuilderInterface $select): InsertQueryBuilder
    {
        $this->queryBuilder->select($select);

        return $this;
    }

    public function getQueryBuilder(): InsertQueryBuilderInterface
    {
        return $this->queryBuilder;
    }

    public function execute(): bool
    {
        return $this->queryExecutor->insert($this->queryBuilder);
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
