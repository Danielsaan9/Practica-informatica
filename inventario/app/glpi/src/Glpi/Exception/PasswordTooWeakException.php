<?php

namespace Glpi\Exception;

use Exception;

class PasswordTooWeakException extends Exception
{
    private array $messages = [];

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
