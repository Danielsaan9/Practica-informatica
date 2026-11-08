<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Twig\Extra\String\StringExtension;

return static function (ContainerConfigurator $container): void {
    // Without Symfony Flex, twig/extra-* packages are not auto-configured.
    // Each one must be registered here: extension (twig.extension tag) and,
    // when the filter uses a runtime, the runtime class (twig.runtime tag)
    // plus any interface binding it depends on.
    // Remember to update this block whenever a new twig/* extra package is added to composer.json.
    $services = $container->services();
    $services->set(StringExtension::class)->tag('twig.extension');
};
