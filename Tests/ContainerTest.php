<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\SymfonyBundle\Tests;

use PHPUnit_Framework_TestCase;
use Puli\SymfonyBundle\PuliBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{
    private $tempDir;

    private $rootDir;

    private $cacheDir;

    protected function setUp()
    {
        while (false === @mkdir($this->tempDir = sys_get_temp_dir().'/puli-bundle/ContainerTest'.rand(10000, 99999), 0777, true)) {}

        $this->rootDir = $this->tempDir.'/root';
        $this->cacheDir = $this->tempDir.'/cache';

        mkdir($this->rootDir);
        mkdir($this->cacheDir);
    }

    protected function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDir);
    }

    public function testContainer()
    {
        $container = $this->createContainer();

        $this->assertInstanceOf('Puli\Repository\Api\ResourceRepository', $container->get('puli.repository'));
        $this->assertInstanceOf('Puli\Discovery\Api\ResourceDiscovery', $container->get('puli.discovery'));
        $this->assertInstanceOf('Puli\UrlGenerator\Api\UrlGenerator', $container->get('puli.url_generator'));
        $this->assertInstanceOf('Puli\SymfonyBridge\Config\FileLocatorChain', $container->get('file_locator'));
    }

    public function testTwigContainer()
    {
        $container = $this->createContainer(array('twig'));

        $this->assertInstanceOf('Twig_Environment', $container->get('twig'));
    }

    private function createContainer(array $templatingEngines = array())
    {
        $frameworkBundle = new FrameworkBundle();
        $twigBundle = new TwigBundle();
        $puliBundle = new PuliBundle();

        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.debug' => false,
            'kernel.bundles' => array(
                'FrameworkBundle' => get_class($frameworkBundle),
                'TwigBundle' => get_class($twigBundle),
                'PuliBundle' => get_class($puliBundle),
            ),
            'kernel.cache_dir' => $this->rootDir,
            'kernel.root_dir' => $this->rootDir,
            'kernel.charset' => 'UTF-8',
            'kernel.secret' => '$ecret',
            'kernel.environment' => 'test',
            'templating.engines' => $templatingEngines,
        )));


        foreach (array($frameworkBundle, $twigBundle, $puliBundle) as $bundle) {
            /** @var BundleInterface $bundle */
            $extension = $bundle->getContainerExtension();
            $container->registerExtension($extension);

            // Load bundle services
            $extension->load(array(), $container);

            // Load compiler passes
            $bundle->build($container);
        }

        $container->addDefinitions(array(
            'kernel' => new Definition('Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest', array(
                'test', // environment
                false, // debug
            )),
        ));

        $container->compile();

        return $container;
    }

}
