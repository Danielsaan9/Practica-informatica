<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Error\ErrorDisplayHandler\HtmlErrorDisplayHandler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Log\AccessLogLineFormatter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ErrorHandlerRequestListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onRequest', ListenersPriority::REQUEST_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        AccessLogLineFormatter::setCurrentRequest($event->getRequest());
        HtmlErrorDisplayHandler::setCurrentRequest($event->getRequest());
    }
}
