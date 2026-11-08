<?php

use Glpi\Application\Environment;
use Glpi\Kernel\Kernel;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/*
 * Closures $_route and $_import are here to provide defaults.
 * These are necessary for prefixing and for locale.
 */
return static function (RoutingConfigurator $routes, Kernel $kernel) {
    $env = Environment::get();
    if (!$env->shouldEnableExtraDevAndDebugTools()) {
        throw new RuntimeException(\sprintf(
            'File "%s" must not be loaded in an environment different than "%s". (current environment: "%s")',
            __FILE__,
            Environment::DEVELOPMENT->value,
            $env->value,
        ));
    }

    if (\class_exists(WebProfilerBundle::class)) {
        $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')->prefix('/_wdt');
        $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')->prefix('/_profiler');
    }
};
