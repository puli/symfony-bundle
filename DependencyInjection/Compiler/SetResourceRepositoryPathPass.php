<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\Extension\Symfony\PuliBundle\DependencyInjection\Compiler;

use Puli\RepositoryManager\ManagerFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\PathUtil\Path;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SetResourceRepositoryPathPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $rootDir = Path::canonicalize($container->getParameter('kernel.root_dir').'/..');

        // Bootstrap necessary Puli classes
        $environment = ManagerFactory::createProjectEnvironment($rootDir);
        $config = $environment->getRootPackageConfig();

        // Read the path of the generated repository
        $repoPath = Path::makeAbsolute($config->getGeneratedResourceRepository(), $rootDir);

        // Set the parameter
        $container->setParameter('puli.repository.path', $repoPath);
    }
}
