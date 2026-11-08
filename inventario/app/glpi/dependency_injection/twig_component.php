<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Glpi\Application\Environment;

return static function (ContainerConfigurator $container): void {
    $container->extension('twig_component', [
        'anonymous_template_directory' => 'twig_components',
        'profiler' => [
            'enabled' => Environment::get()->shouldEnableExtraDevAndDebugTools(),
        ],
        'defaults' => [
            'Twig\\Components\\' => [
                'template_directory' => 'twig_components',
            ],
        ],
    ]);
};
