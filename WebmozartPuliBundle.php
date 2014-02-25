<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Symfony\PuliBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webmozart\Symfony\PuliBundle\DependencyInjection\Compiler\ActivateLazyAssetsPass;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class WebmozartPuliBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ActivateLazyAssetsPass());
    }
}
