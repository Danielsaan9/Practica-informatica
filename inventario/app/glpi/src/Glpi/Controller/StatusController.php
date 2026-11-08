<?php

namespace Glpi\Controller;

use Glpi\Api\HL\Router;
use Glpi\Error\ErrorHandler;
use Glpi\Http\Firewall;
use Glpi\Http\JSONResponse;
use Glpi\Http\Request;
use Glpi\Security\Attribute\SecurityStrategy;
use Session;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final class StatusController extends AbstractController
{
    #[Route(
        "/status.php",
        name: "glpi_status"
    )]
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    public function __invoke(): SymfonyResponse
    {
        // Force in normal mode
        $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

        // Redirect handling to the High-Level API (we may eventually remove this script)
        $request = new Request('GET', '/Status/All', getallheaders());

        try {
            $response = Router::getInstance()->handleRequest($request);
        } catch (Throwable $e) {
            ErrorHandler::logCaughtException($e);
            $response = new JSONResponse(null, 500);
        }

        return new SymfonyResponse(
            (string) $response->getBody(),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }
}
