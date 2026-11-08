<?php

namespace Glpi\Error;

class ErrorUtils
{
    /**
     * Rewrite file paths to not expose their full filesystem path, as it can be considered as a sensitive information.
     *
     * @param string $message
     * @return string
     */
    public static function cleanPaths(string $message): string
    {
        return str_replace(GLPI_ROOT, ".", $message);
    }
}
