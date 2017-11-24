<?php

namespace Xsolve\ModelFactoryTests\Functional\Mock\Object;

interface VolumeInterface
{
    /**
     * @return float
     */
    public function getLength();

    /**
     * @return float
     */
    public function getWidth();

    /**
     * @return float
     */
    public function getHeight();
}
