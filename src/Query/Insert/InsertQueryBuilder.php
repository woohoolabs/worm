<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Query\Insert;

use Closure;
use WoohooLabs\Worm\Connection\ConnectionInterface;
use WoohooLabs\Worm\Query\Select\SelectQueryBuilder;
use WoohooLabs\Worm\Query\Select\SelectQueryInterface;

class InsertQueryBuilder implements InsertQueryBuilderInterface, InsertQueryInterface
{
    /**
     * @var \WoohooLabs\Worm\Connection\ConnectionInterface
     */
    protected $connection;
    /**
     * @var string
     */
    protected $into;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var SelectQueryInterface
     */
    protected $select;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->into = "";
        $this->values = [];
    }

    public function into(string $table): InsertQueryBuilderInterface
    {
        $this->into = $table;

        return $this;
    }

    public function values(array $values): InsertQueryBuilderInterface
    {
        $this->values[] = $values;

        return $this;
    }

    public function select(Closure $select): InsertQueryBuilderInterface
    {
        $this->select = new SelectQueryBuilder($this->connection);

        $select($this->select);

        return $this;
    }

    public function execute(): bool
    {
        $sql = $this->connection->getDriver()->translateInsertQuery($this);

        return $this->connection->execute($sql);
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function getInto(): string
    {
        return $this->into;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getSelect(): SelectQueryInterface
    {
        return $this->select;
    }
}
