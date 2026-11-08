<?php

namespace Glpi\Error\ErrorDisplayHandler;

use Glpi\Application\Environment;
use Session;

final class CliDisplayHandler implements ErrorDisplayHandler
{
    public function canOutput(): bool
    {
        if (\defined('TU_USER')) {
            // Our test suite il already checking logs to ensure that there is no unexpected error triggerred.
            // Displaying of error messages is then disabled to not pollute the test suite results.
            return false;
        }

        return \isCommandLine();
    }

    public function displayErrorMessage(string $error_label, string $message, string $log_level): void
    {
        $is_env_with_debug_tools = Environment::get()->shouldEnableExtraDevAndDebugTools();
        $is_debug_mode = isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE;
        if (!$is_debug_mode && !$is_env_with_debug_tools) {
            // Do not display messages if debug mode is not active and if the environment should not enable debug tools.
            return;
        }

        /**
         * CLI context, no XSS possible.
         *
         * @psalm-taint-escape html
         * @psalm-taint-escape has_quotes
         */
        $output = \sprintf('%s: %s', $error_label, $message) . PHP_EOL;

        echo $output;
    }
}
