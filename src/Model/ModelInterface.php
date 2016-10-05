<?php
namespace WoohooLabs\Worm\Model;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getTable();

    /**
     * @return string
     */
    public function getPrimaryKey();

    /**
     * @return array
     */
    public function getRelationships();
}
