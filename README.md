[![Build Status](https://travis-ci.org/xsolve-pl/xsolve-model-factory.svg?branch=master)](https://travis-ci.org/xsolve-pl/xsolve-model-factory)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xsolve-pl/xsolve-model-factory/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xsolve-pl/xsolve-model-factory/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/xsolve-pl/model-factory/v/stable)](https://packagist.org/packages/xsolve-pl/model-factory)
[![Total Downloads](https://poser.pugx.org/xsolve-pl/model-factory/downloads)](https://packagist.org/packages/xsolve-pl/model-factory)
[![Monthly Downloads](https://poser.pugx.org/xsolve-pl/model-factory/d/monthly)](https://packagist.org/packages/xsolve-pl/model-factory)
[![License](https://poser.pugx.org/xsolve-pl/model-factory/license)](https://packagist.org/packages/xsolve-pl/model-factory)

Table of contents
=================

  * [Introduction](#introduction)
  * [License](#license)
  * [Getting started](#getting-started)
  * [Usage examples](#usage-examples)
    * [Implementing model factory](#implementing-model-factory)
    * [Using external dependencies in model](#using-external-dependencies-in-model)
    * [Grouping model factories into collections](#grouping-model-factories-into-collections)
    * [Creating nested models](#creating-nested-models)

Introduction
============

This library provides a versatile skeleton for organizing model factories.

It can be used to provide objects that will be later on passed to some
serializer and returned via API, or some adapters or facades for your objects
before they are handed over to some other libraries or bundles.

It aims to empower models so that they can easily get access to some services or
create nested models lazily without requiring much work upfront.

If you want to use this library in a Symfony application you may be interested in
using the dedicated bundle available at
[xsolve-pl/model-factory-bundle](https://packagist.org/packages/xsolve-pl/model-factory-bundle).

License
=======

This library is under the MIT license. See the complete license in `LICENSE` file.

Getting started
===============

Include this library in your project using Composer as follows
(assuming it is installed globally):

```bash
$ composer require xsolve-pl/model-factory
```

For more information on Composer see its
[Introduction](https://getcomposer.org/doc/00-intro.md).



Usage examples
==============

Implementing model factory
--------------------------

This library defines a simple interface for model factory that provides
information about whether it supports given object (i.e. is able to produce
model object appropriate for given object) and instantiate such model object.
It also include convenient methods allowing to operate on multiple objects at
once. See `Xsolve\ModelFactory\ModelFactory\ModelFactoryInterface` for
more details.

One you are free to implement this interface with your own model factory
classes, a basic abstract class for model factory is included as well in
`Xsolve\ModelFactory\ModelFactory\ModelFactory`. It includes all the
necessary logic and leaves out only a public `supportsObject` method and a
protected `instantiateModel` method to be implemented. Using it as a base
class creating a new model factory class becomes very easy:

```php
<?php

namespace Example;

use Xsolve\ModelFactory\ModelFactory\ModelFactory;

class FooModelFactory extends ModelFactory
{
    /**
     * {@inheritdoc}
     */
    public function supportsObject($object)
    {
        return ($object instanceof Foo);
    }

    /**
     * {@inheritdoc}
     */
    protected function instantiateModel($object)
    {
        /* @var Foo $object */
        return new FooModel($object);
    }
}
```

Using external dependencies in model
------------------------------------

There are cases where some external dependency is required in model object
to return some value. Simple example would be having an model object
representing a package for which volumetric weight needs to be calculated
(which results from multiplying its volume by some coefficient specific for
each shipment company). A helper class calculating such value would usually
be defined as a service in DI container, with coefficient provided via
some config files or fetched from some data storage.

With this library it is extremely easy to gain access to such services in model
object by utilizing
`Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelInterface`. If
`Xsolve\ModelFactory\ModelFactory\ModelFactory` was used as a base class
for your model factory class, then every model implementing aforementioned
interface will be injected with model factory that was used to produce it.
Since model factories can be defined as services themselves, they can be
injected with any service from DI container and can expose public proxy methods
for model objects to access them.

Following example presents sample usage of this interface. First we define
model factory class:

```php
<?php

namespace Example;

use Xsolve\ModelFactory\ModelFactory\ModelFactory;

class BazModelFactory extends ModelFactory
{
    /**
     * @var VolumetricWeightCalculator
     */
    protected $volumetricWeightCalculator;

    /**
     * @param VolumetricWeightCalculator $volumetricWeightCalculator
     */
    public function __construct(VolumetricWeightCalculator $volumetricWeightCalculator)
    {
        $this->volumetricWeightCalculator = $volumetricWeightCalculator;
    }

    /**
     * @return VolumetricWeightCalculator
     */
    public function getVolumetricWeightCalculator()
    {
        return $this->volumetricWeightCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsObject($object)
    {
        return ($object instanceof Baz);
    }

    /**
     * {@inheritdoc}
     */
    protected function instantiateModel($object)
    {
        /* @var Baz $object */
        return new BazModel($object);
    }
}
```

Our model class would look as follows (note that
`Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelTrait` is
used here to provide convenient `setModelFactory` and `getModelFactory` methods):

```php
<?php

namespace Example;

use Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelInterface;
use Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelTrait;

class BazModel implements ModelFactoryAwareModelInterface
{
    use ModelFactoryAwareModelTrait;

    /**
     * @var Baz
     */
    protected $baz;

    /**
     * @param Baz $baz
     */
    public function __construct(Baz $baz)
    {
        $this->baz = $baz;
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        return ($this->baz->getLength() * $this->baz->getWidth() * $this->baz->getHeight());
    }

    /**
     * @return float
     */
    public function getVolumetricWeight()
    {
        return $this
            ->getModelFactory()
            ->getVolumetricWeightCalculator()
            ->calculate($this->getVolume());
    }
}
```

Grouping model factories into collections
-----------------------------------------

To make it easy to produce models for multiple objects it is possible to
group model factories into collections. If your application provides multiple
API (or multiple API versions that are so different that they utilize
completely different models) you are able to group factories in separate
collections and avoid the risk of producing incorrect models.

Basic implementation of model factory collection is provided in
`Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollection`
class. It allows to register multiple model factories via its
'addModelFactory` method and provides same interface as a single
model factory, so that it is completely interchangable. Its methods attempt
to find appropriate model factory for each object provided.

```php
<?php

namespace Example;

use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollection;

$fooModelFactory = new FooModelFactory();
$bazModelFactory = new BazModelFactory();

$modelFactoryCollection = new ModelFactoryCollection();
$modelFactoryCollection
    ->addModelFactory($fooModelFactory)
    ->addModelFactory($bazModelFactory);

$foo1 = $storage->getFoo(1);
$baz1 = $storage->getBaz(1);

$foo1Model = $modelFactoryCollection->createModel($foo1);
$baz1Model = $modelFactoryCollection->createModel($baz1);

```

This snippet defines one model factory collections `$modelFactoryCollection` and two model
factories `$fooModelFactory` and `$bazModelFactory` which are added to the collection.

Afterwards it is possible to call `createModel` method (as well as other methods
characteristic for model factories) on the collection and model will be created as long as
there is one and only one model factory supporting given object.

Creating nested models
----------------------

In some cases the models you would like to produce can contain other models
(e.g. produced for objects associated with the root object). If this nesting
is deep (as it may be for some APIs optimized for SPA applications that aim
to reduce number of requests required to fetch data) it becomes tedious to
build all models upfront and connect them in a proper way. An easier solution
is to empower models to be able to produce nested models on their own via the
same model factory collection that was used to instantiate themselves.

To achieve this your model may implement
`Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelInterface`
which will result in model factory collection that was used to create the model
to be injected to it. The basic implementation of this interface is provided in
`Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelTrait`.

Let's assume that previously presented `Example\Foo` class object contains a
property containing an array of `Example\Baz` class objects and we want this
association to be carried to model objects as well. If both `Example\FooModelFactory`
and `Example\BazModelFactory` are a part of the same model factory collection
and instances of `Example\FooModel` class are instantiated via collection's
`createModel` or `createModels` methods of this collection, implementation of
`Example\FooModel` class could look as follows:

```php
<?php

namespace Example;

use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelInterface;
use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelTrait;

class FooModel implements ModelFactoryCollectionAwareModelInterface
{
    use ModelFactoryCollectionAwareModelTrait;

    /**
     * @var Foo
     */
    protected $foo;

    /**
     * @param Foo $foo
     */
    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return BazModel[]
     */
    public function getBazs()
    {
        return $this
            ->getModelFactoryCollection()
            ->createModels($this->foo->getBazs());
    }
}
```

Of course if `Example\BazModel` implements the same interface it will also be
injected with the same model factory collection and will be able to produce
models for nested objects - it's as easy as that!
