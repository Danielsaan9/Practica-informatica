<?php

namespace Glpi\Api\HL\Middleware;

class RSQLRequestMiddleware extends AbstractMiddleware implements RequestMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        if ($input->request->hasParameter('filter') && is_array($input->request->getParameter('filter'))) {
            $input->request->setParameter('filter', implode(';', $input->request->getParameter('filter')));
        }
        $next($input);
    }
}
