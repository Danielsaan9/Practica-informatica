<?php

namespace Glpi\Controller\User;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use User;

final class InfoCardController extends AbstractController
{
    #[Route("/User/InfoCard/{users_id}", name: "glpi_user_info_card", methods: ["GET"])]
    public function __invoke(Request $request, int $users_id): Response
    {
        $user = new User();
        if (!$user->can($users_id, READ)) {
            throw new AccessDeniedHttpException();
        }

        if (!$user->getFromDB($users_id)) {
            throw new NotFoundHttpException();
        }

        return new Response($user->getInfoCard());
    }
}
