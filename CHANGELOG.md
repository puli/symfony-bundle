Changelog
=========

* 1.0.0-beta7 (2015-08-12)

 * removed dependency on the "templating.engines" parameter

* 1.0.0-beta6 (2015-05-03)

 * fixed deprecation notes on Symfony 2.7
 * fixed non-working configuration of the PuliExtension service
 * added configuration setting "twig" to disable Twig support
 * removed support for Assetic

* 1.0.0-beta5 (2015-05-29)

 * removed unneeded dependency webmozart/path-util
 * integrated puli/url-generator
 * added puli/composer-plugin dependency

* 1.0.0-beta4 (2015-04-13)

 * moved all code to the `Puli\SymfonyBundle` namespace

* 1.0.0-beta3 (2015-03-19)

 * fixed: added missing root directory to the kernel file locator
 * fixed: the Twig extension and Assetic are only loaded if they are available
 * added support for puli/web-resource-plugin
 
* 1.0.0-beta2 (2015-01-27)

 * adapted to work with version 1.0.0-beta2 of the Puli components

* 1.0.0-beta (2015-01-12)

 * Added support for the @Bundle/.. notation to the assetic factory
 * Made Twig and Assetic optional
 * Added TwigLoaderPass to fix the template loading with the latest version of Symfony 2.5

* 1.0.0-alpha1 (2014-12-03)

 * first alpha release
