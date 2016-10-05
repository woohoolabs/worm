<?php
namespace WoohooLabs\Worm\Query;

use Closure;
use WoohooLabs\Worm\Connection\ConnectionInterface;

interface SelectQueryInterface
{
    /**
     * @return ConnectionInterface
     */
    public function getConnection();

    /**
     * @return array
     */
    public function getSelect();

    /**
     * @return string
     */
    public function getFrom();

    /**
     * @return array
     */
    public function getJoin();

    /**
     * @return ConditionBuilder
     */
    public function getWhere();

    /**
     * @return array
     */
    public function getGroupBy();

    /**
     * @return ConditionBuilder
     */
    public function getHaving();

    /**
     * @return array
     */
    public function getOrderBy();

    /**
     * @return array
     */
    public function getLimit();

    /**
     * @return array
     */
    public function getOffset();
}
