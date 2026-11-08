<?php

namespace Glpi\Exception;

use Exception;
use Glpi\Http\RedirectResponse;

/**
 * @internal Not to be used unless you absolutely know what you are doing.
 *           This will be removed in the future when all the code is under Dependency Injection and proper HTTP routing.
 */
class RedirectException extends Exception
{
    private RedirectResponse $response;

    public function __construct(string $url, int $http_code = 302)
    {
        $this->response = new RedirectResponse($url, $http_code);
    }

    public function getResponse(): RedirectResponse
    {
        return $this->response;
    }
}
