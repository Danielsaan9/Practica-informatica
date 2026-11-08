<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use KnowbaseItem;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Persists whether a knowledge base aside article is collapsed (folded) for the
 * current user.
 */
final class AsideArticleFoldController extends AbstractController
{
    #[Route(
        "/Knowbase/Aside/Article/{id}/Fold",
        name: "knowbase_aside_article_fold",
        requirements: [
            'id' => '\d+',
        ],
        methods: 'POST',
    )]
    #[SecurityStrategy(Firewall::STRATEGY_FAQ_ACCESS)]
    public function __invoke(int $id, Request $request): Response
    {
        $collapsed = $request->getPayload()->get('collapsed');
        if ($collapsed === null) {
            throw new BadRequestHttpException();
        }

        if (!Session::isAuthenticated()) {
            // Nothing to be done as the user don't exist in the database, we
            // can't persist any data.
            return new Response();
        }

        $kb = new KnowbaseItem();
        if (!$kb->getFromDB($id) || !$kb->can($id, READ)) {
            return new Response();
        }

        KnowbaseItem::setUnfoldedForCurrentUser(
            id: $id,
            unfolded: !((bool) $collapsed)
        );

        return new Response(); // OK
    }
}
