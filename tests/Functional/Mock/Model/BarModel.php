<?php

namespace Xsolve\ModelFactoryTests\Functional\Mock\Model;

use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelInterface;
use Xsolve\ModelFactory\ModelFactoryCollection\ModelFactoryCollectionAwareModelTrait;
use Xsolve\ModelFactoryTests\Functional\Mock\Object\Bar;

class BarModel implements ModelFactoryCollectionAwareModelInterface
{
    use ModelFactoryCollectionAwareModelTrait;

    /** @var Bar */
    protected $bar;

    /**
     * @param Bar $bar
     */
    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->bar->getId();
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->bar->getValue();
    }

    /**
     * @return BazModel[]
     */
    public function getBazModelCollection()
    {
        return $this
            ->getModelFactoryCollection()
            ->createModels(
                $this->bar->getBazCollection()
            );
    }
}
