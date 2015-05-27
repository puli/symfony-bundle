<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\SymfonyBundle;

use Puli\SymfonyBundle\DependencyInjection\Compiler\TwigLoaderPass;
use RuntimeException;
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
        parent::build($container);

        if (class_exists('Puli\TwigExtension\PuliExtension')) {
            $container->addCompilerPass(new TwigLoaderPass());
        }
    }
}
