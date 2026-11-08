<?php

namespace Glpi\Error\ErrorDisplayHandler;

interface ErrorDisplayHandler
{
    /**
     * Indicates whether the handler can output an error message in the current execution context.
     */
    public function canOutput(): bool;

    /**
     * Display the error message.
     */
    public function displayErrorMessage(string $error_label, string $message, string $log_level): void;
}
