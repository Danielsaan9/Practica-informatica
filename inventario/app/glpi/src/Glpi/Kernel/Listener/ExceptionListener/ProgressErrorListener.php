<?php

namespace Glpi\Kernel\Listener\ExceptionListener;

use Glpi\Progress\ProgressStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final readonly class ProgressErrorListener implements EventSubscriberInterface
{
    public function __construct(private ProgressStorage $progress_storage) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 1],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        try {
            $this->progress_storage->failCurrentProcessIndicators();
        } catch (Throwable $e) {
            global $PHPLOGGER;
            $PHPLOGGER->error(
                $e->getMessage(),
                ['exception' => $e]
            );
        }
    }
}
