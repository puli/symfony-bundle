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
        $bundles = $container->getParameter('kernel.bundles');
        $templatingEngines = $container->getParameter('templating.engines');

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $twigLoaded = in_array('twig', $templatingEngines)
            && class_exists('Puli\TwigExtension\PuliExtension');
        $asseticLoaded = isset($bundles['AsseticBundle'])
            && class_exists('Puli\AsseticExtension\Factory\PuliAssetFactory');
        $assetPluginLoaded = class_exists('Puli\AssetPlugin\Api\AssetPlugin');

        if ($twigLoaded) {
            $loader->load('twig.xml');
        }

        if ($asseticLoaded) {
            $loader->load('assetic.xml');

            if ($twigLoaded) {
                $loader->load('assetic_twig.xml');
            }
        }

        if ($assetPluginLoaded) {
            $loader->load('asset.xml');

            if ($twigLoaded) {
                $loader->load('asset_twig.xml');
            }
        }
    }
}
