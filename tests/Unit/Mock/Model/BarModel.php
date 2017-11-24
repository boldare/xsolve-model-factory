<?php

namespace Xsolve\ModelFactoryTests\Unit\Mock\Model;

use Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelInterface;
use Xsolve\ModelFactory\ModelFactory\ModelFactoryAwareModelTrait;

class BarModel implements ModelFactoryAwareModelInterface
{
    use ModelFactoryAwareModelTrait;
}
