<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use KnowbaseItem;
use Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Returns the rendered kebab menu items (add to favorites, add to FAQ, delete)
 * for a single knowledge base article. Used by the aside tree, which renders
 * only the kebab trigger and lazy-loads the menu content on hover/open so it
 * never has to load every tree article up-front just to gate the actions.
 */
final class AsideActionsController extends AbstractController
{
    #[Route(
        "/Knowbase/{id}/AsideActions",
        name: "knowbase_aside_actions",
        requirements: [
            'id' => '\d+',
        ],
        methods: 'GET',
    )]
    // Also served to the helpdesk FAQ aside, which lists the same articles.
    #[SecurityStrategy(Firewall::STRATEGY_FAQ_ACCESS)]
    public function __invoke(int $id): Response
    {
        $item = new KnowbaseItem();
        if (!$item->getFromDB($id)) {
            throw new NotFoundHttpException();
        }

        if (!$item->can($id, READ)) {
            // An anonymous visitor must not tell a private article from a missing one.
            throw Session::getLoginUserID() === false ? new NotFoundHttpException() : new AccessDeniedHttpException();
        }

        return $this->render('pages/tools/kb/aside_actions.html.twig', [
            'actions' => $item->getAsideActions(with_move: true),
        ]);
    }
}
