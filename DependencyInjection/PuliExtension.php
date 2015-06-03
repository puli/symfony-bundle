<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class PuliExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $engines = $container->getParameter('templating.engines');

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (method_exists('Symfony\Component\DependencyInjection\Definition', 'setFactory')) {
            $loader->load('services-2.7.xml');
        } else {
            $loader->load('services-2.6.xml');
        }

        $twigEnabledInProject = in_array('twig', $engines);
        $twigExtensionLoaded = class_exists('Puli\TwigExtension\PuliExtension');

        if ($twigEnabledInProject && $twigExtensionLoaded) {
            $loader->load('twig.xml');
        }
    }
}
