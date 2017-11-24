<?php

namespace Xsolve\ModelFactory\ModelFactory;

trait ModelFactoryAwareModelTrait
{
    /** @var ModelFactoryInterface $modelFactory */
    protected $modelFactory;

    /**
     * @param ModelFactoryInterface $modelFactory
     */
    public function setModelFactory(ModelFactoryInterface $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    /**
     * @return ModelFactoryInterface $modelFactory
     */
    public function getModelFactory()
    {
        return $this->modelFactory;
    }
}
