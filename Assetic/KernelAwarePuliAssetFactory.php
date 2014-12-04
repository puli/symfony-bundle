<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\Extension\Symfony\PuliBundle\Assetic;

use Assetic\Asset\AssetInterface;
use Assetic\Asset\AssetReference;
use Puli\Extension\Assetic\Factory\PuliAssetFactory;
use Puli\Repository\ResourceRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * A Symfony-aware extension of Puli's asset factory.
 *
 * The asset manager and the filter manager are loaded lazily from the Symfony
 * DIC. Additionally, support for bundle resources with the "@" notation is
 * implemented like in AsseticBundle.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KernelAwarePuliAssetFactory extends PuliAssetFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ResourceRepositoryInterface $repo, KernelInterface $kernel, ContainerInterface $container, ParameterBagInterface $parameterBag, $baseDir, $debug = false)
    {
        parent::__construct($repo, $baseDir, $debug);

        $this->container = $container;
        $this->kernel = $kernel;
        $this->container = $container;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Copied from AsseticBundle.
     *
     * @param string $input
     * @param array  $options
     *
     * @return AssetInterface
     */
    protected function parseInput($input, array $options = array())
    {
        $input = $this->parameterBag->resolveValue($input);

        // expand bundle notation
        if ('@' == $input[0] && false !== strpos($input, '/')) {
            // use the bundle path as this asset's root
            $bundle = substr($input, 1);
            if (false !== $pos = strpos($bundle, '/')) {
                $bundle = substr($bundle, 0, $pos);
            }
            $options['root'] = array($this->kernel->getBundle($bundle)->getPath());

            // canonicalize the input
            if (false !== $pos = strpos($input, '*')) {
                // locateResource() does not support globs so we provide a naive implementation here
                list($before, $after) = explode('*', $input, 2);
                $input = $this->kernel->locateResource($before).'*'.$after;
            } else {
                $input = $this->kernel->locateResource($input);
            }
        }

        return parent::parseInput($input, $options);
    }

    /**
     * Copied from AsseticBundle.
     *
     * @param $name
     *
     * @return AssetReference
     */
    protected function createAssetReference($name)
    {
        if (!$this->getAssetManager()) {
            $this->setAssetManager($this->container->get('assetic.asset_manager'));
        }

        return parent::createAssetReference($name);
    }

    /**
     * Copied from AsseticBundle.
     *
     * @param $name
     *
     * @return mixed
     */
    protected function getFilter($name)
    {
        if (!$this->getFilterManager()) {
            $this->setFilterManager($this->container->get('assetic.filter_manager'));
        }

        return parent::getFilter($name);
    }
}
