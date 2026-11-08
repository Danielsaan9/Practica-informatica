<?php

namespace Glpi\Controller\ServiceCatalog;

use Entity;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\Form\ServiceCatalog\ServiceCatalog;
use Glpi\Form\ServiceCatalog\ServiceCatalogManager;
use Glpi\Form\ServiceCatalog\SortStrategy\SortStrategyEnum;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use RuntimeException;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    private string $interface;
    private ServiceCatalogManager $service_catalog_manager;

    public function __construct()
    {
        // TODO: replace by autowiring once dependency injection is fully implemented.
        $this->service_catalog_manager = ServiceCatalogManager::getInstance();
        $this->interface = Session::getCurrentInterface();
    }

    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    #[Route(
        "/ServiceCatalog",
        name: "glpi_service_catalog",
        methods: "GET"
    )]
    public function __invoke(Request $request): Response
    {
        $session = Session::getCurrentSessionInfo();
        $parameters = new FormAccessParameters(
            session_info: Session::getCurrentSessionInfo(),
            url_parameters: $request->query->all()
        );

        // Make sure service catalog is enabled
        if ($session === null) {
            throw new AccessDeniedHttpException();
        }
        $entity = Entity::getById($session->getCurrentEntityId());
        if (!$entity) {
            // Safety check, will never happen but help with static analysis.
            throw new RuntimeException("Cant load current entity");
        }
        if (!$entity->isServiceCatalogEnabled()) {
            throw new AccessDeniedHttpException();
        }

        $item_request = new ItemRequest(
            access_parameters: $parameters,
            category_id: 0,
            sort_strategy: $entity->getServiceCatalogDefaultSortStrategy(),
        );
        $items = $this->service_catalog_manager->getItems($item_request);

        return $this->render('pages/self-service/service_catalog.html.twig', [
            'title' => __("New ticket"),
            'menu'  => $this->interface == "central"
                ? ["helpdesk", ServiceCatalog::class]
                : ["create_ticket"],
            'items' => $items,
            'sort_strategies' => SortStrategyEnum::getAvailableStrategies(),
            'default_sort_strategy' => $entity->getServiceCatalogDefaultSortStrategy()->value,
            'expand_categories' => $entity->shouldExpandCategoriesInServiceCatalog(),
        ]);
    }
}
