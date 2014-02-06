<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Symfony\PuliBundle\LocatorFactory;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ComposerLocatorFactory
{
    public static function createResourceLocator($pathToGeneratedLocator)
    {
        return require $pathToGeneratedLocator;
    }
}