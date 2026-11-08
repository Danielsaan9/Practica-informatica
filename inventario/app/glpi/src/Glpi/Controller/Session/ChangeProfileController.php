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

final class ChangeProfileController extends AbstractController
{
    #[Route(
        "/Session/ChangeProfile",
        name: "glpi_change_profile",
        methods: "POST",
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function __invoke(Request $request): Response
    {
        global $CFG_GLPI;

        // Validate profile
        $profile_id = $request->request->getInt('id');
        if (!isset($_SESSION["glpiprofiles"][$profile_id])) {
            throw new AccessDeniedHttpException();
        }

        // Apply new profile
        Session::changeProfile($profile_id);

        // If the profile change was made with an AJAX request, this mean this
        // was some background script and we do not need to redirect it to
        // another page.
        if ($request->isXmlHttpRequest()) {
            return new Response();
        }

        // Compute redirection URL
        if (Session::getCurrentInterface() == "helpdesk") {
            $go_to_create_ticket = $_SESSION['glpiactiveprofile']['create_ticket_on_login'];
            $route = $go_to_create_ticket ? "/ServiceCatalog" : "/Helpdesk";
            $redirect = $request->getBasePath() . $route;
        } else {
            $redirect = Html::getBackUrl();
            $separator = str_contains($redirect, '?') ? "&" : "?";
            $redirect = $redirect . $separator . '_redirected_from_profile_selector=true';
        }

        return new RedirectResponse($redirect);
    }
}
