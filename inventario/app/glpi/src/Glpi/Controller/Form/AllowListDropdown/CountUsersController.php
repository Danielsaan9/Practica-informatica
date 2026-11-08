<?php

namespace Glpi\Controller\Form\AllowListDropdown;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\AccessControl\ControlType\AllowListDropdown;
use Glpi\Form\Form;
use Group;
use Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use User;

final class CountUsersController extends AbstractController
{
    #[Route(
        path: "/Form/AllowListDropdown/CountUsers",
        name: "form_allow_list_dropdown_count_users",
        methods: "GET"
    )]
    public function __invoke(Request $request): Response
    {
        if (!Form::canView()) {
            throw new AccessDeniedHttpException();
        }

        $values = $request->query->all()['values'] ?? [];
        if (empty($values)) {
            // Empty $values mean no criteria has been defined in the dropdown.
            // No users should be found.
            $users = [-1];
            $groups = [-1];
            $profiles = [-1];

            // Do not display the link if there are no criteria, as the search
            // would be confusing with the '-1' criteria.
            $do_not_display_link = true;
        } else {
            $users = AllowListDropdown::getPostedIds($values, User::class);
            $groups = AllowListDropdown::getPostedIds($values, Group::class);
            $profiles = AllowListDropdown::getPostedIds($values, Profile::class);
            $do_not_display_link = false;
        }

        $data = AllowListDropdown::countUsersForCriteria(
            $users,
            $groups,
            $profiles
        );

        if ($do_not_display_link || !User::canView()) {
            unset($data['link']);
        }

        return new JsonResponse($data);
    }
}
