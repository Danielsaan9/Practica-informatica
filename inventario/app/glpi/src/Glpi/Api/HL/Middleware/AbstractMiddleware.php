<?php

namespace Glpi\Api\HL\Middleware;

abstract class AbstractMiddleware
{
    /**
     * @param MiddlewareInput $input
     * @param callable $next
     * @return mixed
     */
    public function __invoke(MiddlewareInput $input, callable $next)
    {
        if (method_exists($this, 'process')) {
            return $this->process($input, $next);
        }
        return null;
    }
}
