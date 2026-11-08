<?php

namespace Glpi\Controller\Traits;

use Glpi\Progress\StoredProgressIndicator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Toolbox;

use function Safe\fastcgi_finish_request;
use function Safe\ob_end_clean;
use function Safe\session_write_close;

trait AsyncOperationProgressControllerTrait
{
    /**
     * Return the response to be used by the `ProgressIndicator` js module to be able to follow the operation progress.
     *
     * @param callable $operation_callable  The callable corresponding to the operation to execute.
     */
    protected function getProgressInitResponse(
        StoredProgressIndicator $progress_indicator,
        callable $operation_callable
    ): StreamedResponse {
        Toolbox::safeIniSet('max_execution_time', '300'); // Allow up to 5 minutes to prevent unexpected timeout
        session_write_close(); // Prevent the session file lock to block the progress check requests

        // Be sure to disable the output buffering.
        // It is necessary to make the `flush()` works as expected.
        while (\ob_get_level() > 0) {
            ob_end_clean();
        }

        return new StreamedResponse(
            function () use ($progress_indicator, $operation_callable) {
                echo $progress_indicator->getStorageKey();

                // Send headers and content.
                // The browser will consider that the response is complete due to the `Connection: close` header
                // and will not have to wait for operation to finish to consider the request as ended.
                \flush();

                if (\function_exists('fastcgi_finish_request')) {
                    // In PHP-FPM context, it indicates to the client (Apache, Nginx, ...)
                    // that the request is finished.
                    fastcgi_finish_request();
                }

                // Prevent the request to be terminated by the client.
                \ignore_user_abort(true);

                $operation_callable();
            },
            headers: [
                'Content-Type'   => 'text/html',
                'Content-Length' => \strlen($progress_indicator->getStorageKey()),
                'Cache-Control'  => 'no-cache,no-store',
                'Connection'     => 'close',
            ]
        );
    }
}
