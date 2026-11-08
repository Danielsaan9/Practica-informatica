<?php

namespace Glpi\Api\HL\Middleware;

use Session;

class DebugRequestMiddleware extends AbstractMiddleware implements RequestMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        if (
            Session::haveRight(\Config::$rightname, UPDATE)
            && $input->request->hasHeader('X-Debug-Mode')
            && filter_var($input->request->getHeaderLine('X-Debug-Mode'), FILTER_VALIDATE_BOOLEAN)
        ) {
            $_SESSION['glpi_use_mode'] = Session::DEBUG_MODE;
        }
        $next($input);
    }
}
