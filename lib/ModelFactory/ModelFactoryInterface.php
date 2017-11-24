<?php

namespace Xsolve\ModelFactory\ModelFactory;

use Traversable;
use Xsolve\ModelFactory\ModelFactoryCollection\Exception\ModelFactoryCollectionException;

interface ModelFactoryInterface
{
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function supportsObject($object);

    /**
     * @param array|Traversable $objects
     *
     * @return bool
     */
    public function supportsObjects($objects);

    /**
     * @param array|Traversable $objects
     *
     * @return mixed
     *
     * @throws ModelFactoryCollectionException
     */
    public function createModels($objects);

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function createModel($object);
}
