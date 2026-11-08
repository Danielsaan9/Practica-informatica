<?php

namespace Glpi\Exception;

use Exception;
use Throwable;

class AuthenticationFailedException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        private array $authentication_errors = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getAuthenticationErrors(): array
    {
        return $this->authentication_errors;
    }
}
