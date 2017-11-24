<?php

namespace Xsolve\ModelFactory\ModelFactory;

interface ModelFactoryAwareModelInterface
{
    /**
     * @param ModelFactoryInterface $modelFactory
     */
    public function setModelFactory(ModelFactoryInterface $modelFactory);
}
