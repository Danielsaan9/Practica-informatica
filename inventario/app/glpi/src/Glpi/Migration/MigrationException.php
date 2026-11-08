<?php

namespace Glpi\Migration;

use RuntimeException;
use Throwable;

/**
 * @final
 */
class MigrationException extends RuntimeException
{
    private string $localized_message;

    public function __construct(
        string $localized_message,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->localized_message = $localized_message;

        parent::__construct($message, $code, $previous);
    }

    public function getLocalizedMessage(): string
    {
        return $this->localized_message;
    }
}
