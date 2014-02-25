<?php

/*
 * This file is part of the Symfony Puli Bundle.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Symfony\PuliBundle\Assetic\Twig;

use Assetic\Factory\AssetFactory;
use Assetic\ValueSupplierInterface;
use Assetic\Extension\Twig\AsseticExtension as BaseAsseticExtension;
use Symfony\Bundle\AsseticBundle\Twig\AsseticExtension;
use Symfony\Component\Templating\TemplateNameParserInterface;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LazyAsseticExtension extends AsseticExtension
{
    public function __construct(AssetFactory $factory, TemplateNameParserInterface $templateNameParser, $useController = false, $functions = array(), $enabledBundles = array(), ValueSupplierInterface $valueSupplier = null, $lazyAssets = false)
    {
        AsseticExtension::__construct(
            $factory,
            $templateNameParser,
            $useController,
            $functions,
            $enabledBundles,
            $valueSupplier
        );

        // Call again, this time with the $lazyAssets argument
        BaseAsseticExtension::__construct($factory, $functions, $valueSupplier, $lazyAssets);
    }

}
