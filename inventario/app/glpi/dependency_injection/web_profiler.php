<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Glpi\Application\Environment;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;

return static function (ContainerConfigurator $container): void {
    if (!Environment::get()->shouldEnableExtraDevAndDebugTools()) {
        // Don't use the web profiler elsewhere than dev.
        return;
    }

    if (\class_exists(WebProfilerBundle::class)) {
        $container->extension('web_profiler', [
            'toolbar' => true,
        ]);
    }

    $container->extension('framework', [
        'profiler' => ['only_exceptions' => false],
    ]);
};
