<?php

namespace Glpi\Error\ErrorDisplayHandler;

use Glpi\Application\Environment;
use Session;
use Symfony\Component\HttpFoundation\Request;

final class HtmlErrorDisplayHandler implements ErrorDisplayHandler
{
    private static ?Request $currentRequest = null;

    public static function setCurrentRequest(Request $request): void
    {
        self::$currentRequest = $request;
    }

    public function canOutput(): bool
    {
        if (self::$currentRequest === null) {
            return false;
        }

        return self::$currentRequest->getPreferredFormat() === 'html';
    }

    public function displayErrorMessage(string $error_label, string $message, string $log_level): void
    {
        $is_env_with_debug_tools = Environment::get()->shouldEnableExtraDevAndDebugTools();
        $is_debug_mode = isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE;
        if (!$is_debug_mode && !$is_env_with_debug_tools) {
            // Do not display messages if debug mode is not active and if the environment should not enable debug tools.
            return;
        }

        echo \sprintf(
            '<div class="alert alert-important alert-danger glpi-debug-alert"><span class="fw-bold">%s: </span>%s</div>',
            \htmlescape($error_label),
            \htmlescape($message)
        );
    }
}
