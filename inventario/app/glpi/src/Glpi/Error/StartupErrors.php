<?php

namespace Glpi\Error;

/**
 * Registry of the PHP errors that occurred during the request startup.
 *
 * These errors (`max_input_vars` overflow, `post_max_size` overflow, ...) are raised by PHP itself
 * before any userland code is executed. They can therefore not be caught by an error handler, and
 * `error_get_last()` is the only way to know about them.
 *
 * As their most visible effect is to silently truncate the input superglobals, not reporting them
 * leads to very confusing symptoms (e.g. a massive action processing only a part of the selected
 * items, or a CSRF token missing from the submitted data).
 *
 * ⚠ This class is loaded manually, before the Composer autoloader, so it must not have any
 * dependency that would be needed at load time.
 *
 * The captured error is reported by the `CheckStartupErrorsListener` request listener.
 */
final class StartupErrors
{
    /**
     * @var array{type: int, message: string, file: string, line: int}|null
     */
    private static ?array $error = null;

    private static bool $captured = false;

    /**
     * Capture the PHP startup error, if any.
     *
     * It must be called as early as possible in the request lifecycle, as any error triggered
     * later would overwrite the value returned by `error_get_last()`.
     *
     * @param array{type: int, message: string, file: string, line: int}|null $php_error
     *        Error to capture instead of the one returned by `error_get_last()`. Tests only.
     */
    public static function capture(?array $php_error = null): void
    {
        if (self::$captured) {
            // Only the very first call is relevant, as any later call may capture an error
            // that has been triggered by the GLPI code itself.
            return;
        }

        self::$captured = true;
        self::$error    = $php_error ?? \error_get_last();
    }

    /**
     * Return the captured startup error, if any.
     *
     * @return array{type: int, message: string, file: string, line: int}|null
     */
    public static function get(): ?array
    {
        return self::$error;
    }

    /**
     * Return the reason for which the input data has been truncated during the request startup,
     * or `null` if the captured error (if any) did not truncate the input data.
     */
    public static function getTruncationReason(): ?InputTruncationReason
    {
        if (self::$error === null) {
            return null;
        }

        return InputTruncationReason::fromPhpErrorMessage(self::$error['message']);
    }

    /**
     * Reset the registry. Tests only.
     */
    public static function reset(): void
    {
        self::$error    = null;
        self::$captured = false;
    }
}
