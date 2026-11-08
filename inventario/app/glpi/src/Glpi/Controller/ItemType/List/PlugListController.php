<?php

namespace Glpi\Controller\ItemType\List;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Routing\Attribute\ItemtypeListRoute;
use Plug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PlugListController extends AbstractController
{
    #[ItemtypeListRoute(Plug::class)]
    public function __invoke(Request $request): Response
    {
        // Plugs have no standalone list page, they are only browsed from their parent Support item's tab.
        throw new NotFoundHttpException();
    }
}
