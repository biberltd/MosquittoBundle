<?php

namespace BiberLtd\Bundles\MosquittoBundle;
use BiberLtd\Bundles\MosquittoBundle\DependencyInjection\AutoLoad;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BiberLtdBundlesMosquittoBundle extends Bundle

{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AutoLoad\LoadRouters());
    }
}
