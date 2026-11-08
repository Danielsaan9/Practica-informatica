<?php

namespace Glpi\Controller\Helpdesk;

use Glpi\Controller\AbstractController;
use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\ServiceCatalog\HomeSearchManager;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[SecurityStrategy(Firewall::STRATEGY_HELPDESK_ACCESS)]
    #[Route(
        "/Helpdesk/Search",
        name: "glpi_helpdesk_search",
        methods: "GET"
    )]
    public function __invoke(Request $request): Response
    {
        // Read parameters
        $filter = $request->query->getString('filter');

        // Filter items
        $manager = HomeSearchManager::getInstance();
        $items_request = new ItemRequest(
            access_parameters: new FormAccessParameters(
                session_info: Session::getCurrentSessionInfo(),
            ),
            filter: $filter,
        );
        $items = $manager->getItems($items_request);

        return $this->render('pages/helpdesk/search.html.twig', [
            'items_by_label' => $items,
        ]);
    }
}
