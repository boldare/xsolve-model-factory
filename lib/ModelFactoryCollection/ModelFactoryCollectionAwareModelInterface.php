<?php

namespace Xsolve\ModelFactory\ModelFactoryCollection;

interface ModelFactoryCollectionAwareModelInterface
{
    /**
     * @param ModelFactoryCollectionInterface $modelFactoryCollection
     */
    public function setModelFactoryCollection(ModelFactoryCollectionInterface $modelFactoryCollection);
}
