<?php

namespace Glpi\Error\ErrorDisplayHandler;

use Glpi\Application\Environment;
use Psr\Log\LogLevel;
use Session;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsoleErrorDisplayHandler implements ErrorDisplayHandler
{
    private static ?OutputInterface $output = null;

    public static function setOutput(OutputInterface $output): void
    {
        self::$output = $output;
    }

    public function canOutput(): bool
    {
        return self::$output !== null;
    }

    public function displayErrorMessage(string $error_label, string $message, string $log_level): void
    {
        $format = 'comment';
        switch ($log_level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
                $format    = 'error';
                $verbosity = OutputInterface::VERBOSITY_QUIET;
                break;
            case LogLevel::WARNING:
                $is_env_with_debug_tools = Environment::get()->shouldEnableExtraDevAndDebugTools();
                $is_debug_mode = isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE;
                if (!$is_debug_mode && !$is_env_with_debug_tools) {
                    // If debug mode is not active and if the environment should not enable debug tools,
                    // display warnings only when verbose mode is activated.
                    $verbosity = OutputInterface::VERBOSITY_VERBOSE;
                } else {
                    $verbosity = OutputInterface::VERBOSITY_NORMAL;
                }
                break;
            case LogLevel::NOTICE:
            case LogLevel::INFO:
            default:
                $verbosity = OutputInterface::VERBOSITY_VERBOSE;
                break;
            case LogLevel::DEBUG:
                $verbosity = OutputInterface::VERBOSITY_VERY_VERBOSE;
                break;
        }

        self::$output->writeln(
            \sprintf(
                '<%1$s>%2$s</%1$s>',
                $format,
                $error_label . ': ' . $message
            ),
            $verbosity
        );
    }
}
