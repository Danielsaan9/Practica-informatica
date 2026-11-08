<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use KnowbaseItem_Favorite;
use Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ToggleFavoriteController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/{id}/ToggleFavorite",
        name: "knowbase_toggle_favorite",
        requirements: [
            'id' => '\d+',
        ],
        methods: 'POST',
    )]
    // Favorites are also offered by the helpdesk FAQ view; per-article rights are enforced below by the CRUD trait.
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $value = $request->getPayload()->get('value');

        if ($value === null) {
            throw new BadRequestHttpException();
        }

        $user_id = Session::getLoginUserID();

        $criteria = [
            'knowbaseitems_id' => $id,
            'users_id'         => $user_id,
        ];

        $favorite = new KnowbaseItem_Favorite();
        $exists   = $favorite->getFromDBByCrit($criteria);

        if ($value && !$exists) {
            $this->add(KnowbaseItem_Favorite::class, $criteria);
        } elseif (!$value && $exists) {
            $this->purge(KnowbaseItem_Favorite::class, $favorite->getID());
        }

        return new JsonResponse(['favorite' => (bool) $value]);
    }
}
