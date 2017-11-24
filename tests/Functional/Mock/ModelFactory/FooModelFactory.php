<?php

namespace Xsolve\ModelFactoryTests\Functional\Mock\ModelFactory;

use Xsolve\ModelFactory\ModelFactory\ModelFactory;
use Xsolve\ModelFactoryTests\Functional\Mock\Model\FooModel;
use Xsolve\ModelFactoryTests\Functional\Mock\Object\Foo;

class FooModelFactory extends ModelFactory
{
    /**
     * {@inheritdoc}
     */
    public function supportsObject($object)
    {
        return $object instanceof Foo;
    }

    /**
     * @param Foo $object
     *
     * @return FooModel
     */
    protected function instantiateModel($object)
    {
        return new FooModel($object);
    }
}
