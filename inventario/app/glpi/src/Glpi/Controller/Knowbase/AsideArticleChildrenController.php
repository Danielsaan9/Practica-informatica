<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Http\Firewall;
use Glpi\Knowbase\Aside\Builder;
use Glpi\Security\Attribute\SecurityStrategy;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Renders the children of a single knowledge base article, for the aside tree.
 *
 * The tree is folded by default and renders a folded article without its
 * children: rendering the whole knowledge base up front is what makes a large
 * one expensive. This fills a branch in when the reader unfolds it.
 */
final class AsideArticleChildrenController extends AbstractController
{
    #[Route(
        "/Knowbase/Aside/Article/{id}/Children",
        name: "knowbase_aside_article_children",
        requirements: [
            'id' => '\d+',
        ],
        methods: 'GET',
    )]
    // Also served to the helpdesk FAQ aside, which lists the same articles.
    #[SecurityStrategy(Firewall::STRATEGY_FAQ_ACCESS)]
    public function __invoke(int $id, Request $request): Response
    {
        if (!KnowbaseItem::canView()) {
            throw new AccessDeniedHttpException();
        }

        // Visibility is applied by the builder, which returns nothing for an
        // article the current user may not see.
        $children = (new Builder($request->query->getInt('current_id')))->buildChildren($id);

        return $this->render('pages/tools/kb/aside_children.html.twig', [
            'children'     => $children,
            'can_create'   => KnowbaseItem::canCreateAsideTree(),
            'show_actions' => KnowbaseItem::canShowAsideActions(),
        ]);
    }
}
