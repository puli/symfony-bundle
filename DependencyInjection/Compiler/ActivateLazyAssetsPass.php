<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Symfony\PuliBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ActivateLazyAssetsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('assetic.twig_extension')) {
            return;
        }

        $extension = $container->getDefinition('assetic.twig_extension');
        $extension->setClass('Webmozart\Symfony\PuliBundle\Assetic\Twig\LazyAsseticExtension');

        // Activate lazy assets
        $arguments = $extension->getArguments();
        $arguments[6] = true; // $lazyAssets

        $extension->setArguments($arguments);
    }
}
