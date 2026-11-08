<?php

namespace Glpi\Api\HL\Middleware;

use Session;

/**
 * This middleware is not loaded by default when using the API.
 * It may be manually added on the Router instance in cases where GLPI itself needs to access the API.
 * If a user is already logged in, this middleware will allow the request to continue.
 */
class InternalAuthMiddleware extends AbstractMiddleware implements AuthMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        if (Session::getLoginUserID(false) || Session::isRightChecksDisabled()) {
            $input->client = [
                'client_id' => 'internal', // Internal just means the user was authenticated internally either by cookie or an already existing session.
                'users_id'  => Session::getLoginUserID(),
                'scopes' => [],
            ];
            $input->response = null;
        } else {
            $next($input);
        }
    }
}
