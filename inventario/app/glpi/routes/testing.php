<?php

use Glpi\Kernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/*
 * Closures $_route and $_import are here to provide defaults.
 * These are necessary for prefixing and for locale.
 */
return static function (RoutingConfigurator $routes, Kernel $kernel) {
    $routes->import($kernel->getProjectDir() . '/tests/src/Controller', 'attribute');
};
