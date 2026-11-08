<?php

namespace Glpi\Controller\Form;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Form\AccessControl\FormAccessControlManager;
use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\Dropdown\FormActorsDropdown;
use Glpi\Form\Form;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuestionActorsDropdownController extends AbstractController
{
    #[Route(
        "/Form/Question/ActorsDropdown",
        name: "glpi_form_question_actors_dropdown_value",
        methods: "POST"
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function __invoke(Request $request): Response
    {
        $this->checkFormAccessPolicies($request);

        $options = [
            'allowed_types'    => $request->request->all('allowed_types'),
            'right_for_users'  => $request->request->getString('right_for_users', 'all'),
            'group_conditions' => $request->request->all('group_conditions'),
            'page'             => $request->request->getInt('page', 1),
            'page_size'        => $request->request->getInt('page_limit', -1),
        ];

        if (
            !Session::validateIDOR([
                'itemtype'    => FormActorsDropdown::class,
                '_idor_token' => $request->request->getString('_idor_token'),
            ] + $options)
        ) {
            throw new AccessDeniedHttpException();
        }

        return new JsonResponse(
            FormActorsDropdown::fetchValues(
                $request->request->getString('searchText'),
                $options
            )
        );
    }

    private function loadTargetForm(Request $request): Form
    {
        $forms_id = (int) $request->request->getInt('form_id');
        if (!$forms_id) {
            throw new BadRequestHttpException();
        }

        $form = Form::getById($forms_id);
        if (!$form instanceof Form) {
            throw new NotFoundHttpException();
        }

        return $form;
    }

    private function checkFormAccessPolicies(Request $request): void
    {
        $form_access_manager = FormAccessControlManager::getInstance();

        if (!Session::haveRight(Form::$rightname, READ)) {
            $form = $this->loadTargetForm($request);

            // Load current user session info and URL parameters.
            $parameters = new FormAccessParameters(
                session_info: Session::getCurrentSessionInfo(),
                url_parameters: $request->query->all(),
            );

            if (!$form_access_manager->canAnswerForm($form, $parameters)) {
                throw new AccessDeniedHttpException();
            }
        }
    }
}
