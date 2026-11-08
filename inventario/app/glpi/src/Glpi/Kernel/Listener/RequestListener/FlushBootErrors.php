<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Error\ErrorHandler;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class FlushBootErrors implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', ListenersPriority::REQUEST_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onKernelRequest(): void
    {
        ErrorHandler::disableBufferAndFlushMessages();
    }
}
