<?php

namespace Glpi\Log;

use Monolog\LogRecord;
use Override;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessLogHandler extends AbstractLogHandler
{
    public function __construct()
    {
        parent::__construct(GLPI_LOG_DIR . '/access-errors.log');

        $this->setFormatter(new AccessLogLineFormatter());
    }

    #[Override()]
    public function isHandling(LogRecord $record): bool
    {
        if (
            !isset($record->context['exception'])
            || !($record->context['exception'] instanceof AccessDeniedHttpException)
        ) {
            // Do not log anything else than "access denied" exceptions.
            return false;
        }

        return parent::isHandling($record);
    }
}
