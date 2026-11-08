<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Kernel\ListenersPriority;
use Glpi\Toolbox\IPUtilities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class ConfigureTrustedProxies implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', ListenersPriority::REQUEST_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        IPUtilities::configureTrustedProxies(GLPI_TRUSTED_REVERSE_PROXIES, GLPI_REVERSE_PROXY_HEADERS);
    }
}
