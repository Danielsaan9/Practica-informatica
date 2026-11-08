<?php

namespace Glpi\Controller;

use Exception;

final class RequestException extends Exception
{
    protected int $http_code;

    /**
     * @param int $http_code
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($http_code, $message, $code = 0, $previous = null)
    {
        $this->http_code = $http_code;
        parent::__construct($message, $code, $previous);
    }

    public function getHttpCode(): int
    {
        return $this->http_code;
    }
}
