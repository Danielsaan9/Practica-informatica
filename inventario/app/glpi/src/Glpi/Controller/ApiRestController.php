<?php

namespace Glpi\Controller;

use Glpi\Api\APIRest;
use Glpi\Http\HeaderlessStreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiRestController extends AbstractController
{
    #[Route(
        "/apirest.php{request_parameters}",
        name: "glpi_api_rest",
        requirements: [
            'request_parameters' => '.*',
        ]
    )]
    public function __invoke(Request $request): Response
    {
        $_SERVER['PATH_INFO'] = $request->attributes->get('request_parameters');

        // @phpstan-ignore-next-line method.deprecatedClass (refactoring is planned later)
        return new HeaderlessStreamedResponse(function () {
            $api = new APIRest();
            $api->call();
        });
    }
}
