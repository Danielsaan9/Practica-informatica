<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use function Safe\ini_get;

class SessionCheckCookieListener implements EventSubscriberInterface
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
            // Do not check cookies configuration on sub requests.
            return;
        }

        if ($this->isFrontEndAssetEndpoint($event->getRequest())) {
            // Do not check cookies configuration on front-end assets endpoints.
            return;
        }

        // If session cookie is only available on a secure HTTPS context but request is made on an unsecured HTTP context,
        // throw an exception
        $cookie_secure = filter_var(ini_get('session.cookie_secure'), FILTER_VALIDATE_BOOLEAN);
        if ($event->getRequest()->isSecure() === false && $cookie_secure === true) {
            $exception = new BadRequestHttpException();
            $exception->setMessageToDisplay(__('The web server is configured to allow session cookies only on secured context (https). Therefore, you must access GLPI on a secured context to be able to use it.'));
            throw $exception;
        }
    }
}
