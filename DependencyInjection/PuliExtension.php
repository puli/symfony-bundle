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
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class PuliExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (method_exists('Symfony\Component\DependencyInjection\Definition', 'setFactory')) {
            $loader->load('services-2.7.xml');
        } else {
            $loader->load('services-2.6.xml');
        }

        $twigBundleLoaded = isset($bundles['TwigBundle']);
        $twigExtensionLoaded = class_exists('Puli\TwigExtension\PuliExtension');
        $twigEnabled = $config['twig'];

        if ($twigBundleLoaded && $twigExtensionLoaded && $twigEnabled) {
            $loader->load('twig.xml');
        }

        $this->addClassesToCompile(array(
            'Puli\Discovery\AbstractEditableDiscovery',
            'Puli\Discovery\Api\Binding\Binding',
            'Puli\Discovery\Api\Binding\Initializer\BindingInitializer',
            'Puli\Discovery\Api\Discovery',
            'Puli\Discovery\Api\EditableDiscovery',
            'Puli\Repository\AbstractJsonRepository',
            'Puli\Repository\AbstractRepository',
            'Puli\Repository\AbstractEditableRepository',
            'Puli\Repository\Api\ChangeStream\ChangeStream',
            'Puli\Repository\Api\EditableRepository',
            'Puli\Repository\Api\ResourceRepository',
            'Puli\Repository\Api\Resource\BodyResource',
            'Puli\Repository\Api\Resource\FilesystemResource',
            'Puli\Repository\Api\Resource\PuliResource',
            'Puli\Repository\Api\Resource\ResourceMetadata',
            'Puli\Repository\OptimizedJsonRepository',
            'Puli\Repository\Resource\AbstractFilesystemResource',
            'Puli\Repository\Resource\FileResource',
            'Puli\Repository\Resource\GenericResource',
        ));
    }
}
