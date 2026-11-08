<?php

namespace Glpi\Api\HL\Middleware;

class SecurityResponseMiddleware extends AbstractMiddleware implements ResponseMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        // Inject header to prevent MIME sniffing
        $input->response = $input->response->withHeader('X-Content-Type-Options', 'nosniff');
        // CORS
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $input->response = $input->response->withHeader('Access-Control-Allow-Origin', '*');   // cache for 1 day
        }
        if ($input->request->getMethod() === 'GET' || $input->request->getMethod() === 'OPTIONS') {
            $input->response = $input->response->withHeader('Access-Control-Expose-Headers', ['Content-Type', 'Content-Range', 'Accept-Ranges']);
        }
        $next($input);
    }
}
