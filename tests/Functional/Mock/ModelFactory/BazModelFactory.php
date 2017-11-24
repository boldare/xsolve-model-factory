<?php

namespace Xsolve\ModelFactoryTests\Functional\Mock\ModelFactory;

use Xsolve\ModelFactory\ModelFactory\ModelFactory;
use Xsolve\ModelFactoryTests\Functional\Mock\Helper\VolumetricWeightHelper;
use Xsolve\ModelFactoryTests\Functional\Mock\Model\BazModel;
use Xsolve\ModelFactoryTests\Functional\Mock\Object\Baz;

class BazModelFactory extends ModelFactory
{
    /** @var VolumetricWeightHelper */
    protected $volumetricWeightHelper;

    /**
     * @param VolumetricWeightHelper $volumetricWeightHelper
     */
    public function __construct(VolumetricWeightHelper $volumetricWeightHelper)
    {
        $this->volumetricWeightHelper = $volumetricWeightHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsObject($object)
    {
        return $object instanceof Baz;
    }

    /**
     * @param Baz $object
     *
     * @return float
     */
    public function getBazVolumetricWeight(Baz $object)
    {
        return $this->volumetricWeightHelper->getVolumetricWeight($object);
    }

    /**
     * @param Baz $object
     *
     * @return BazModel
     */
    protected function instantiateModel($object)
    {
        return new BazModel($object);
    }
}
