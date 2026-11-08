<?php

namespace Glpi\Kernel\Listener\RequestListener;

use Glpi\Error\StartupErrors;
use Glpi\Exception\Http\HttpException;
use Glpi\Kernel\ListenersPriority;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Report the PHP errors that occurred during the request startup.
 *
 * @see StartupErrors
 */
final class CheckStartupErrorsListener implements EventSubscriberInterface
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
            // The startup errors are related to the whole PHP request, they are already
            // handled during the main request.
            return;
        }

        $error = StartupErrors::get();
        if ($error === null) {
            return;
        }

        // Startup errors are raised before the error handler is registered, so they never reach
        // the GLPI logs. Log them explicitly, otherwise there would be no trace of them.
        \trigger_error(
            \sprintf(
                '%s (initially triggered at %s line %d)',
                $error['message'],
                $error['file'],
                $error['line']
            ),
            $this->mapErrorLevelToUserLevel($error['type'])
        );

        $reason = StartupErrors::getTruncationReason();
        if ($reason === null) {
            // The error did not alter the input data, the request can still be processed.
            return;
        }

        // A part of the input data has been dropped by PHP. Processing the request would make
        // GLPI operate on incomplete data  so it has to be rejected with an explicit message.
        $exception = new HttpException($reason->getStatusCode());
        $exception->setMessageToDisplay($reason->getMessageToDisplay());
        throw $exception;
    }

    private function mapErrorLevelToUserLevel(int $level): int
    {
        return match ($level) {
            E_WARNING           => E_USER_WARNING,
            E_CORE_WARNING      => E_USER_WARNING,
            E_COMPILE_WARNING   => E_USER_WARNING,
            E_USER_WARNING      => E_USER_WARNING,
            E_NOTICE            => E_USER_NOTICE,
            E_USER_NOTICE       => E_USER_NOTICE,
            E_DEPRECATED        => E_USER_DEPRECATED,
            E_USER_DEPRECATED   => E_USER_DEPRECATED,
            default             => E_USER_WARNING, // should not happen, other error are blocking the script execution
        };
    }
}
