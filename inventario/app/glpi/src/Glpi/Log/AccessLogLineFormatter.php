<?php

namespace Glpi\Log;

use Monolog\LogRecord;
use Override;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class AccessLogLineFormatter extends AbstractLogLineFormatter
{
    private static ?Request $currentRequest = null;

    public function __construct()
    {
        parent::__construct(
            format: null, // cannot be handled this way, our format is too specific
            dateFormat: 'Y-m-d H:i:s',
            allowInlineLineBreaks: true,
            ignoreEmptyContextAndExtra: true,
        );
    }

    public static function setCurrentRequest(Request $request): void
    {
        self::$currentRequest = $request;
    }

    #[Override()]
    public function format(LogRecord $record): string
    {
        /** @var Throwable $exception */
        $exception = $record->context['exception'];

        $requested_uri = self::$currentRequest->getPathInfo();
        if (($qs = self::$currentRequest->getQueryString()) !== null) {
            $requested_uri .= '?' . $qs;
        }

        $user_id = Session::getLoginUserID() ?: 'Anonymous';

        $message = match ($exception::class) {
            AccessDeniedHttpException::class => sprintf(
                'User ID: `%s` tried to access or perform an action on `%s` with insufficient rights.',
                $user_id,
                $requested_uri
            ),
            NotFoundHttpException::class => sprintf(
                'User ID: `%s` tried to access a non-existent item on `%s`.',
                $user_id,
                $requested_uri
            ),
            default => sprintf(
                'User ID: `%s` tried to execute an invalid request on `%s`.',
                $user_id,
                $requested_uri
            ),
        };

        $line = sprintf(
            "[%s] %s\n",
            $this->formatDate($record->datetime),
            $message
        );

        if (($exception_message = $exception->getMessage()) !== '') {
            $line .= sprintf('  Additional information: %s', $exception_message);
        }

        $line .= $this->normalizeException($exception);

        return $line;
    }
}
