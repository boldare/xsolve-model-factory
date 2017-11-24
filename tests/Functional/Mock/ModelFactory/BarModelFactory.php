<?php

namespace Xsolve\ModelFactoryTests\Functional\Mock\ModelFactory;

use Xsolve\ModelFactory\ModelFactory\ModelFactory;
use Xsolve\ModelFactoryTests\Functional\Mock\Model\BarModel;
use Xsolve\ModelFactoryTests\Functional\Mock\Object\Bar;

class BarModelFactory extends ModelFactory
{
    /**
     * {@inheritdoc}
     */
    public function supportsObject($object)
    {
        return $object instanceof Bar;
    }

    /**
     * @param Bar $object
     *
     * @return BarModel
     */
    protected function instantiateModel($object)
    {
        return new BarModel($object);
    }
}
