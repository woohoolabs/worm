<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Execution;

use WoohooLabs\Worm\Model\ModelInterface;

class ModelContainer
{
    /**
     * @var array
     */
    private $models;

    public function __construct()
    {
        $this->models = [];
    }

    /**
     * @param ModelInterface|string $model
     */
    public function get($model): ModelInterface
    {
        if ($model instanceof ModelInterface) {
            $class = get_class($model);

            if (isset($this->models[$class]) === false) {
                $this->models[$class] = $model;
            }

            return $model;
        }

        if (isset($this->models[$model]) === false) {
            $this->models[$model] = new $model();
        }

        return $this->models[$model];
    }
}
