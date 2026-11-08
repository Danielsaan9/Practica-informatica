<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Glpi\Controller\ErrorController;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'error_controller' => ErrorController::class,
    ]);
};
