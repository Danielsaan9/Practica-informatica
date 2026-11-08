<?php

namespace Glpi\Api\HL\Middleware;

interface ResponseMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void;

    /**
     * @param MiddlewareInput $input
     * @param callable $next
     * @return void
     */
    public function __invoke(MiddlewareInput $input, callable $next);
}
