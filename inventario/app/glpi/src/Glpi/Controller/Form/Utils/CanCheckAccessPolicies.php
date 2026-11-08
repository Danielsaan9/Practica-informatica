<?php

namespace Glpi\Controller\Form\Utils;

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\SessionExpiredException;
use Glpi\Form\AccessControl\FormAccessControlManager;
use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\Form;
use Session;
use Symfony\Component\HttpFoundation\Request;

trait CanCheckAccessPolicies
{
    protected function checkFormAccessPolicies(Form $form, Request $request): void
    {
        $form_access_manager = FormAccessControlManager::getInstance();

        if (Session::haveRight(Form::$rightname, READ)) {
            // Form administrators can bypass restrictions while previewing forms.
            $parameters = new FormAccessParameters(bypass_restriction: true);
        } else {
            $url_parameters = $request->query->all();

            // Load current user session info and URL parameters.
            $parameters = new FormAccessParameters(
                session_info: Session::getCurrentSessionInfo(),
                url_parameters: $url_parameters,
            );
        }
        // If the user is not logged in and the form require a valid session,
        // redirect him to the login page instead.
        // Note that the session validity will still be checked by the `canAnswerForm`
        if (
            !Session::isAuthenticated()
            && !$form_access_manager->allowUnauthenticatedAccess($form)
        ) {
            // Will trigger a redirection to the loggin page
            throw new SessionExpiredException();
        }
        // Must be authenticated here
        if (!$form_access_manager->canAnswerForm($form, $parameters)) {

            throw new AccessDeniedHttpException();
        }
    }
}
