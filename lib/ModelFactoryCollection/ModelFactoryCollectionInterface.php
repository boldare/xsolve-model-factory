<?php

namespace Xsolve\ModelFactory\ModelFactoryCollection;

use Xsolve\ModelFactory\ModelFactory\ModelFactoryInterface;

interface ModelFactoryCollectionInterface extends ModelFactoryInterface
{
    /**
     * @param ModelFactoryInterface $modelFactory
     * 
     * @return $this
     */
    public function addModelFactory(ModelFactoryInterface $modelFactory);
}
