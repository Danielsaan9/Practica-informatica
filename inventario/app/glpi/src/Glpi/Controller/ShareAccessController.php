<?php

namespace Glpi\Controller;

use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Http\Firewall;
use Glpi\Http\RedirectResponse;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\Security\ShareTokenManager;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShareAccessController extends AbstractController
{
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    #[Route(
        "/Share/{token}",
        name: "glpi_share_access",
        methods: "GET",
        requirements: ['token' => '[0-9a-f]{64}'],
    )]
    public function __invoke(Request $request, string $token): Response
    {
        $token_manager = new ShareTokenManager();

        $shared_item = $token_manager->getSharedItem($token);
        if ($shared_item === null) {
            throw new NotFoundHttpException();
        }

        // User who can already read the item: redirect to normal item URL.
        // Not `can()`: it also accepts the session grant of an earlier visit.
        if (
            Session::isAuthenticated()
            && $shared_item::canView()
            && $shared_item->canViewItem()
        ) {
            $response = new RedirectResponse($shared_item->getItemUrl());
            $response->headers->set('Referrer-Policy', 'no-referrer');
            return $response;
        }

        // Other users: grant access and render the shared item.
        $token_manager->grantSessionAccess($token);
        $response = $this->render(
            $shared_item->getShareableViewTemplate(),
            $shared_item->getShareableViewParams(),
        );
        $response->headers->set('Referrer-Policy', 'no-referrer');
        return $response;
    }
}
