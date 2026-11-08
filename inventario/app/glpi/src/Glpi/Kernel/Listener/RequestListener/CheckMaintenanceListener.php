<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Controller\MaintenanceController;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class CheckMaintenanceListener implements EventSubscriberInterface
{
    use KernelListenerTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', ListenersPriority::REQUEST_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // Do not check DB maintenance mode on sub-requests.
            return;
        }

        if (
            $this->isFrontEndAssetEndpoint($event->getRequest())
            || $this->isSymfonyProfilerEndpoint($event->getRequest())
        ) {
            // These resources should always be available.
            return;
        }

        global $CFG_GLPI;

        // Check maintenance mode
        if (!isset($CFG_GLPI["maintenance_mode"]) || !$CFG_GLPI["maintenance_mode"]) {
            return;
        }

        if ($event->getRequest()->query->get('skipMaintenance')) {
            $_SESSION["glpiskipMaintenance"] = 1;
            return;
        }

        if (isset($_SESSION["glpiskipMaintenance"]) && $_SESSION["glpiskipMaintenance"]) {
            return;
        }

        // Setting the `_controller` attribute will force Symfony to consider that routing was resolved already.
        // @see `\Symfony\Component\HttpKernel\EventListener\RouterListener::onKernelRequest()`
        $event->getRequest()->attributes->set('_controller', MaintenanceController::class);
    }
}
