<?php

namespace Glpi\Api\HL\GraphQL\Error;

use GraphQL\Error\Error;
use GraphQL\Language\Source;

class FieldAccessDeniedError extends Error
{
    public function __construct($nodes = null, ?Source $source = null, ?array $positions = null, ?array $path = null, ?\Throwable $previous = null, ?array $extensions = null, ?array $unaliasedPath = null)
    {
        $field_path = implode('.', $path ?? []);
        $message = "You do not have permission to view the field $field_path";
        parent::__construct($message, $nodes, $source, $positions, $path, $previous, $extensions, $unaliasedPath);
    }

    public function isClientSafe(): bool
    {
        return true;
    }
}
