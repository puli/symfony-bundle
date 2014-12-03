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

use Puli\Extension\Assetic\Factory\PuliAssetFactory;
use Puli\Repository\ResourceRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loads the AssetManager and FilterManager on-demand from the Symfony DIC.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ContainerAwarePuliAssetFactory extends PuliAssetFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ResourceRepositoryInterface $repo, ContainerInterface $container, $debug = false)
    {
        parent::__construct($repo, $debug);

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
