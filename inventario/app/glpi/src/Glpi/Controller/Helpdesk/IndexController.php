<?php

namespace Glpi\Controller\Helpdesk;

use Entity;
use Glpi\Controller\AbstractController;
use Glpi\Helpdesk\HomePageTabs;
use Glpi\Helpdesk\Tile\TilesManager;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use User;

final class IndexController extends AbstractController
{
    private TilesManager $tiles_manager;

    public function __construct()
    {
        $this->tiles_manager = TilesManager::getInstance();
    }

    #[SecurityStrategy(Firewall::STRATEGY_HELPDESK_ACCESS)]
    #[Route(
        "/Helpdesk",
        name: "glpi_helpdesk_index",
        methods: "GET"
    )]
    public function __invoke(Request $request): Response
    {
        $session_info = Session::getCurrentSessionInfo();

        /** @var User $user */
        $user = User::getById($session_info->getUserId());
        /** @var Entity $entity */
        $entity = Entity::getById($session_info->getCurrentEntityId());

        return $this->render('pages/helpdesk/index.html.twig', [
            'title' => __("Home"),
            'menu'  => ['helpdesk-home'],
            'tiles' => $this->tiles_manager->getVisibleTilesForSession(Session::getCurrentSessionInfo()),
            'tabs'  => new HomePageTabs(),
            'password_alert' => $user->getPasswordExpirationMessage(),
            'entity' => $entity,
        ]);
    }
}
