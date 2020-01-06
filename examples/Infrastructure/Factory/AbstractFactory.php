<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Infrastructure\Factory;

use LogicException;
use WoohooLabs\Worm\Execution\IdentityMap;
use WoohooLabs\Worm\Model\ModelInterface;

abstract class AbstractFactory
{
    private IdentityMap $identityMap;

    public function __construct(IdentityMap $identityMap)
    {
        $this->identityMap = $identityMap;
    }

    protected function createObject(ModelInterface $model, array $entity, callable $factory)
    {
        if ($model->getId($entity) === null) {
            throw new LogicException("The ID of the record must be present!");
        }

        return $this->identityMap->createObject(
            $model,
            $entity,
            $factory
        );
    }
}
