<?php

namespace Glpi\Api\HL\Middleware;

use Glpi\Api\HL\Controller\AbstractController;
use Glpi\Api\HL\Route;
use Glpi\Api\HL\Router;
use Glpi\Application\Environment;

/**
 * Handles OAuth scopes
 */
class OAuthRequestMiddleware extends AbstractMiddleware implements RequestMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        $route_path = $input->route_path->getRoutePath();
        if ($input->route_path->getRouteSecurityLevel() === Route::SECURITY_NONE || (isCommandLine() && Environment::get() !== Environment::TESTING)) {
            $next($input);
            return;
        }
        // If OAuth scopes are expanded to be more endpoint-specific, the Route attributes should be updated with the ability to specify the scopes,
        // and the middleware should check the scopes against the scopes allowed/requested for the client.
        //TODO Handle 'inventory' scope. I guess the agent communication needs redirected through the API.

        if (strcasecmp($route_path, '/Administration/User/Me/Emails/Default') === 0) {
            $scopes_required = ['OR' => ['email', 'user']];
        } elseif (
            strcasecmp($route_path, '/Administration/User/Me') === 0
            || str_starts_with(strtolower($route_path), '/administration/user/me/')
        ) {
            $scopes_required = ['OR' => ['user']];
        } elseif (str_starts_with(strtolower($route_path), '/status')) {
            $scopes_required = ['OR' => ['status']];
        } elseif (str_starts_with(strtolower($route_path), '/graphql')) {
            $scopes_required = ['OR' => ['graphql']];
        } else {
            $scopes_required = ['OR' => ['api']];
        }

        $client = Router::getInstance()->getCurrentClient();
        if ($client === null) {
            $input->response = AbstractController::getAccessDeniedErrorResponse();
            return;
        } elseif ($client['client_id'] === 'internal') {
            // No scope restrictions for internal clients
            $next($input);
            return;
        }
        foreach ($scopes_required['OR'] as $scope) {
            if (in_array($scope, $client['scopes'] ?? [], true)) {
                $next($input);
                return;
            }
        }

        $input->response = AbstractController::getAccessDeniedErrorResponse('You do not have the required scope(s) to access this endpoint.');
    }
}
