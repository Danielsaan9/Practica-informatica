<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Http\RedirectResponse;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class RedirectLegacyRouteListener implements EventSubscriberInterface
{
    use KernelListenerTrait;

    private const URLS_MAPPING = [
        '/front/helpdesk.public.php' => '/Helpdesk',
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', ListenersPriority::REQUEST_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($this->isControllerAlreadyAssigned($request)) {
            // A controller has already been assigned by a previous listener, do not override it.
            return;
        }

        if (\array_key_exists($request->getPathInfo(), self::URLS_MAPPING)) {
            $event->setResponse(new RedirectResponse($request->getBasePath() . self::URLS_MAPPING[$request->getPathInfo()]));
        }
    }
}
