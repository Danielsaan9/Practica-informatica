<?php

namespace Glpi\Log;

use Monolog\Level;
use Monolog\LogRecord;
use Override;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorLogHandler extends AbstractLogHandler
{
    public function __construct()
    {
        parent::__construct(GLPI_LOG_DIR . '/php-errors.log');

        $this->setFormatter(new ErrorLogLineFormatter());
    }

    #[Override()]
    public function isHandling(LogRecord $record): bool
    {
        // Do not log "Notified event {...}" messages.
        if (isset($record->context['event']) && $record->level === Level::Debug) {
            return false;
        }

        // Do not log "Matched route "{...}"" messages.
        if (isset($record->context['route']) && $record->level === Level::Info) {
            return false;
        }

        // Do not log access errors.
        // 4xx errors logging is done by the `\Glpi\Log\AccessLogHandler` handler.
        if (isset($record->context['exception'])) {
            /** @var Throwable $exception */
            $exception = $record->context['exception'];
            if (
                $exception instanceof HttpExceptionInterface
                && $exception->getStatusCode() >= 400
                && $exception->getStatusCode() < 500
            ) {
                return false;
            }
        }

        return parent::isHandling($record);
    }
}
