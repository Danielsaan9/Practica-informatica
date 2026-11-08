<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Glpi\Application\Environment;
use Glpi\DependencyInjection\PublicService;
use Glpi\Error\ErrorHandler;
use Glpi\Security\ReAuth\ReAuthManager;

return static function (ContainerConfigurator $container): void {
    $projectDir = dirname(__DIR__);
    $parameters = $container->parameters();
    $services = $container->services();

    // Default secret, just in case
    $parameters->set('glpi.default_secret', bin2hex(random_bytes(32)));
    $parameters->set('env(APP_SECRET_FILE)', $projectDir . '/config/glpicrypt.key');
    $parameters->set('kernel.secret', env('default:glpi.default_secret:file:APP_SECRET_FILE'));

    // Prevent low level errors (e.g. warning) to be converted to exception in dev environment
    $parameters->set('debug.error_handler.throw_at', ErrorHandler::FATAL_ERRORS);

    $services = $services
        ->defaults()
            ->autowire()
            ->autoconfigure()
        ->instanceof(PublicService::class)->public()
    ;

    $services->load('Glpi\Controller\\', $projectDir . '/src/Glpi/Controller');
    $services->load('Glpi\Http\\', $projectDir . '/src/Glpi/Http');
    $services->load('Glpi\Kernel\\Listener\\', $projectDir . '/src/Glpi/Kernel/Listener');
    $services->load('Glpi\DependencyInjection\\', $projectDir . '/src/Glpi/DependencyInjection');
    $services->load('Glpi\Progress\\', $projectDir . '/src/Glpi/Progress')
        ->exclude([
            $projectDir . '/src/Glpi/Progress/ConsoleProgressIndicator.php',
            $projectDir . '/src/Glpi/Progress/StoredProgressIndicator.php',
        ]);
    $services->load(
        'Glpi\Form\Condition\\',
        $projectDir . '/src/Glpi/Form/Condition/*Manager.php'
    );
    $services->load(
        'Glpi\UI\\',
        $projectDir . '/src/Glpi/UI/*Manager.php'
    );
    $services->load(
        'Glpi\Application\View\\',
        $projectDir . '/src/Glpi/Application/View'
    );
    $services->load(
        'Twig\Components\\',
        $projectDir . '/src/Twig/Components'
    );
    $services->set(ReAuthManager::class)
        ->factory([ReAuthManager::class, 'getInstance']);

    // Test-only controllers (e2e/Playwright/Cypress helper endpoints), routed via
    // routes/testing.php and routes/e2e_testing.php. Registered as services only in
    // test environments so they can be autowired; they are never routable elsewhere.
    if (Environment::get()->shouldEnableTestResources()) {
        $services->load('Glpi\Tests\Controller\\', $projectDir . '/tests/src/Controller');
    }

    // Prevent Symfony to register its own default logger.
    // @see \Symfony\Component\HttpKernel\DependencyInjection\LoggerPass
    $services->set('logger')->synthetic();
};
