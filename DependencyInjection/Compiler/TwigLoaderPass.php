<?php

/*
 * This file is part of the puli/symfony-bundle package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\SymfonyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Moves the "puli.twig.template_loader" service before the
 * "twig.loader.filesystem" service.
 *
 * This is necessary since the following PR:
 * https://github.com/symfony/symfony/pull/12894
 *
 * If not done, the PuliTemplateLoader is never invoked, because the filesystem
 * loader already resolves the Puli template path using the PuliFileLocator.
 * The responsible line is TemplateNameParser:59 which does not throw an
 * exception anymore.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TwigLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('twig')) {
            return;
        }

        $chainLoader = $container->getDefinition('twig.loader.chain');

        $methodCalls = $chainLoader->getMethodCalls();
        $symfonyKey = null;
        $puliKey = null;

        // By default, the filesystem loader is added before the Puli loader
        // However, that prevents the Puli loader from being used at all, so
        // we need to insert the Puli loader right before the filesystem
        // loader
        foreach ($methodCalls as $key => $methodCall) {
            if ($this->isAddLoaderCall($methodCall, 'twig.loader.filesystem')) {
                $symfonyKey = $key;
                continue;
            }

            if ($this->isAddLoaderCall($methodCall, 'puli.twig.template_loader')) {
                $puliKey = $key;
                continue;
            }
        }

        // Move the Puli loader before the filesystem loader if necessary
        if (null !== $symfonyKey && null !== $puliKey && $puliKey > $symfonyKey) {
            $puliLoaderCall = $methodCalls[$puliKey];
            unset($methodCalls[$puliKey]);

            array_splice($methodCalls, $symfonyKey, 0, array($puliLoaderCall));

            $chainLoader->setMethodCalls($methodCalls);
        }
    }

    private function isAddLoaderCall(array $methodCall, $serviceId)
    {
        return 'addLoader' === $methodCall[0] && isset($methodCall[1][0])
            && $methodCall[1][0] instanceof Reference
            && $serviceId === (string) $methodCall[1][0];
    }
}

