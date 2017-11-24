<?php

namespace Xsolve\ModelFactoryTests\Unit\Mock\Model;

use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelInterface;
use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelTrait;

class BazModel implements ModelFactoryCollectionAwareModelInterface
{
    use ModelFactoryCollectionAwareModelTrait;
}
