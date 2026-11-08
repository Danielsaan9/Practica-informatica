<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Controller\InventoryController;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class CatchInventoryAgentRequestListener implements EventSubscriberInterface
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
        $request = $event->getRequest();

        if ($this->isControllerAlreadyAssigned($request)) {
            // A controller has already been assigned by a previous listener, do not override it.
            return;
        }

        if (
            $request->getPathInfo() === '/'
            && $request->isMethod('POST')
            && !$request->request->has('totp_code')
            && $request->getContent() !== ''
        ) {
            $sub_request = $request->duplicate();

            // Setting the `_controller` attribute will force Symfony to consider that routing was resolved already.
            // @see `\Symfony\Component\HttpKernel\EventListener\RouterListener::onKernelRequest()`
            $sub_request->attributes->set('_controller', InventoryController::class . '::index');

            $response = $event->getKernel()->handle($sub_request, HttpKernelInterface::SUB_REQUEST);
            $event->setResponse($response);
        }
    }
}
