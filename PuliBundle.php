<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\Extension\Symfony\PuliBundle;

use Puli\Extension\Symfony\PuliBundle\DependencyInjection\Compiler\TwigLoaderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class PuliBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (!defined('PULI_FACTORY_CLASS')) {
            throw new \RuntimeException(sprintf(
                "The PULI_FACTORY_CLASS constant is missing. Resolutions:\n".
                "(1) Install \"puli/cli\" and run \"bin/puli build\".\n".
                "(2) Install \"puli/factory\", implement \"Puli\\Factory\\PuliFactory\" and set the constant manually.\n".
                "If you don't know, do (1)."
            ));
        }

        parent::build($container);

        $container->addCompilerPass(new TwigLoaderPass());
    }
}
