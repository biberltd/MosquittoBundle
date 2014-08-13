<?php

namespace BiberLtd\Bundles\ExifBundle;
use BiberLtd\Bundles\ExifBundle\DependencyInjection\AutoLoad;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BiberLtdBundlesExifBundle extends Bundle

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
