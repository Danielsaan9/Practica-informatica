<?php

namespace Glpi\Controller;

use Glpi\Api\APIRest;
use Glpi\Api\HL\Controller\AbstractController as ApiAbstractController;
use Glpi\Api\HL\Router;
use Glpi\Api\HL\StreamedResponseWrapper;
use Glpi\Error\ErrorHandler;
use Glpi\Http\HeaderlessStreamedResponse;
use Glpi\Http\JSONResponse;
use Glpi\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_replace;

final class ApiController extends AbstractController
{
    #[Route(
        "/api.php{request_parameters}",
        name: "glpi_api",
        requirements: [
            'request_parameters' => '.*',
        ]
    )]
    public function __invoke(SymfonyRequest $request): SymfonyResponse
    {
        $_SERVER['PATH_INFO'] = $request->attributes->get('request_parameters');

        $method = $_SERVER['REQUEST_METHOD'];
        $relative_uri = $_SERVER['PATH_INFO'] ?? '';
        // Ensure uri starts with slash but does not end with a slash
        $relative_uri = '/' . trim($relative_uri, '/');

        // If the relative URI starts with /v1/ or is /v1 then we are dealing with a legacy API request
        if (preg_match('/^\/v1(\/|$)/', $relative_uri)) {
            // @phpstan-ignore-next-line method.deprecatedClass (refactoring is planned later)
            return new HeaderlessStreamedResponse(function () {
                $api = new APIRest();
                $api->call();
            });
        }

        // Extract the requested API version (if any) and then remove it from the URI
        $version = Router::API_VERSION;
        if (preg_match('/^\/v(\d+(?:\.\d+)*)\//', $relative_uri, $matches)) {
            $version = $matches[1];
            $relative_uri = preg_replace('/^\/v\d+(?:\.\d+)*\//', '/', $relative_uri);
        }
        $version = Router::normalizeAPIVersion($version);

        $body = file_get_contents('php://input');

        $headers = getallheaders();
        $headers['GLPI-API-Version'] = $version;
        $request = new Request($method, $relative_uri, $headers, $body);

        $router = Router::getInstance();

        try {
            $response = $router->handleRequest($request);
        } catch (InvalidArgumentException $e) {
            $response = new JSONResponse(
                ApiAbstractController::getErrorResponseBody(
                    ApiAbstractController::ERROR_INVALID_PARAMETER,
                    $e->getMessage()
                ),
                400
            );
        } catch (Throwable $e) {
            ErrorHandler::logCaughtException($e);
            $response = new JSONResponse(null, 500);
        }

        if ($response instanceof StreamedResponseWrapper) {
            return $response->getSymfonyResponse();
        }

        return new SymfonyResponse(
            (string) $response->getBody(),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }
}
