<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query;

use WoohooLabs\Larva\Query\Condition\ConditionBuilder;
use WoohooLabs\Larva\Query\Condition\ConditionBuilderInterface;
use WoohooLabs\Larva\Query\Delete\DeleteQueryBuilder as LarvaDeleteQueryBuilder;
use WoohooLabs\Larva\Query\Delete\DeleteQueryBuilderInterface;
use WoohooLabs\Worm\Execution\QueryExecutor;
use WoohooLabs\Worm\Model\ModelInterface;

class DeleteQueryBuilder
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var QueryExecutor
     */
    private $queryExecutor;

    /**
     * @var LarvaDeleteQueryBuilder
     */
    private $queryBuilder;

    public function __construct(ModelInterface $model, QueryExecutor $executor)
    {
        $this->model = $model;
        $this->queryExecutor = $executor;
        $this->queryBuilder = new LarvaDeleteQueryBuilder();
        $this->queryBuilder->from($model->getTable());
    }

    public function where(ConditionBuilderInterface $where): DeleteQueryBuilder
    {
        $this->queryBuilder->where($where);

        return $this;
    }

    public function addWhereGroup(ConditionBuilderInterface $where): DeleteQueryBuilder
    {
        $this->queryBuilder->addWhereGroup($where);

        return $this;
    }

    public function getQueryBuilder(): DeleteQueryBuilderInterface
    {
        return $this->queryBuilder;
    }

    public function execute(): bool
    {
        return $this->queryExecutor->delete($this->queryBuilder);
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function executeById($id): bool
    {
        $this->queryBuilder
            ->addWhereGroup(
                ConditionBuilder::create()
                    ->columnToValue($this->model->getPrimaryKey(), "=", $id)
            );

        return $this->queryExecutor->delete($this->queryBuilder);
    }
}
