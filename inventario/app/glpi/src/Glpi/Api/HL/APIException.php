<?php

namespace Glpi\Api\HL;

use Exception;
use Throwable;

/**
 * An exception thrown by the API.
 * A user message can be provided to be displayed to the user.
 * Otherwise, only a generic message will be displayed to the user.
 */
class APIException extends Exception
{
    private string $user_message;

    private string|array|null $details;

    public function __construct(string $message = '', string $user_message = '', string|array|null $details = null, int $code = 0, ?Throwable $previous = null)
    {
        if ($user_message === '') {
            $user_message = __('An error occurred while processing your request.');
        }
        if ($message === '') {
            $message = $user_message;
        }
        $this->user_message = $user_message;
        $this->details = $details;
        parent::__construct($message, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->user_message;
    }

    public function getDetails(): string|array|null
    {
        return $this->details;
    }
}
