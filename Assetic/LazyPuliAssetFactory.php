<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Symfony\PuliBundle\Assetic;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Webmozart\Puli\Extension\Assetic\Factory\PuliAssetFactory;
use Webmozart\Puli\Locator\ResourceLocatorInterface;

/**
 * Loads the AssetManager and FilterManager on-demand from the Symfony DIC.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LazyPuliAssetFactory extends PuliAssetFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ResourceLocatorInterface $locator, ContainerInterface $container, $debug = false)
    {
        parent::__construct($locator, $debug);

        $this->container = $container;
    }

    protected function createAssetReference($name)
    {
        if (!$this->getAssetManager()) {
            $this->setAssetManager($this->container->get('assetic.asset_manager'));
        }

        return parent::createAssetReference($name);
    }

    protected function getFilter($name)
    {
        if (!$this->getFilterManager()) {
            $this->setFilterManager($this->container->get('assetic.filter_manager'));
        }

        return parent::getFilter($name);
    }
}
