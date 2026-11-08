<?php

namespace Glpi\Api\HL\Middleware;

use Glpi\Http\Response;

interface AuthMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void;

    /**
     * @param MiddlewareInput $input
     * @param callable $next
     * @return ?Response
     */
    public function __invoke(MiddlewareInput $input, callable $next);
}
