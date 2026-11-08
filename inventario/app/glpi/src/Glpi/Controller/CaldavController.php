<?php

namespace Glpi\Controller;

use Glpi\CalDAV\Server;
use Glpi\Http\HeaderlessStreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CaldavController extends AbstractController
{
    #[Route(
        "/caldav.php{request_parameters}",
        name: "glpi_caldav",
        requirements: [
            'request_parameters' => '.*',
        ]
    )]
    public function __invoke(Request $request): Response
    {
        // @phpstan-ignore-next-line method.deprecatedClass (refactoring is planned later)
        return new HeaderlessStreamedResponse(function () {
            global $CFG_GLPI;

            $server = new Server();
            $server->setBaseUri($CFG_GLPI['root_doc'] . '/caldav.php');
            $server->start();
        });
    }
}
