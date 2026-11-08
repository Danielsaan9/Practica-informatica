<?php

namespace Glpi\Controller\Session;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Http\Firewall;
use Glpi\Http\RedirectResponse;
use Glpi\Security\Attribute\SecurityStrategy;
use Html;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChangeEntityController extends AbstractController
{
    #[Route(
        "/Session/ChangeEntity",
        name: "glpi_change_entity",
        methods: "POST",
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function __invoke(Request $request): Response
    {
        // Read parameters
        $full_structure = $request->request->getBoolean('full_structure');
        $entity_id      = $full_structure ? 'all' : $request->request->getInt('id');
        $is_recursive   = $request->request->getBoolean('is_recursive');

        // Try to load new entity
        if (!Session::changeActiveEntities($entity_id, $is_recursive)) {
            throw new AccessDeniedHttpException();
        }

        // If the profile change was made with an AJAX request, this mean this
        // was some background script and we do not need to redirect it to
        // another page.
        if ($request->isXmlHttpRequest()) {
            return new Response();
        }

        // Redirect to previous page
        $redirect = Html::getBackUrl();
        return new RedirectResponse($redirect);
    }
}
