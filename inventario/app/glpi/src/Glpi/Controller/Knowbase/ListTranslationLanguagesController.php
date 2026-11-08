<?php

namespace Glpi\Controller\Knowbase;

use Dropdown;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use KnowbaseItem;
use KnowbaseItemTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListTranslationLanguagesController extends AbstractController
{
    #[Route(
        "/Knowbase/KnowbaseItem/{knowbaseitems_id}/Languages",
        name: "knowbaseitem_languages",
        requirements: [
            'knowbaseitems_id' => '\d+',
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->getInt('knowbaseitems_id');

        $kbitem = new KnowbaseItem();
        if (!$kbitem->getFromDB($id)) {
            throw new NotFoundHttpException();
        }
        if (!$kbitem->can($id, READ) || !KnowbaseItemTranslation::canView()) {
            throw new AccessDeniedHttpException();
        }

        $all_languages = Dropdown::getLanguages();
        $existing = KnowbaseItemTranslation::getAlreadyTranslatedForItem($kbitem);

        $languages = [];
        foreach ($all_languages as $code => $name) {
            $languages[] = [
                'code' => $code,
                'name' => $name,
                'has_translation' => isset($existing[$code]),
            ];
        }

        return new JsonResponse([
            'languages' => $languages,
        ]);
    }
}
