<?php

namespace Glpi\Controller\Form;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use User;

final class DelegationController extends AbstractController
{
    #[Route(
        "/Form/Delegation",
        name: "glpi_form_delegation",
        methods: "GET",
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function __invoke(Request $request): Response
    {
        $selected_user_id = $request->query->get('selected_user_id');
        if (empty($selected_user_id)) {
            throw new BadRequestHttpException('Missing selected_user_id parameter');
        }

        $selected_user = new User();
        if (!$selected_user->getFromDB($selected_user_id)) {
            throw new NotFoundHttpException('Selected user not found');
        }
        return $this->render('components/helpdesk_forms/delegation_alert.html.twig', [
            'selected_user' => $selected_user,
        ]);
    }
}
