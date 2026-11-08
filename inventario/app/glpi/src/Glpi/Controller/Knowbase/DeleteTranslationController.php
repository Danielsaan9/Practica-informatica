<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\NotFoundHttpException;
use KnowbaseItem;
use KnowbaseItemTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteTranslationController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/KnowbaseItem/{knowbaseitems_id}/Translation/{language}/Delete",
        name: "knowbaseitem_translation_delete",
        methods: ["POST"],
        requirements: [
            'knowbaseitems_id' => '\d+',
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->getInt('knowbaseitems_id');
        $language = $request->attributes->getString('language');

        $kbitem = new KnowbaseItem();
        if (!$kbitem->getFromDB($id)) {
            throw new NotFoundHttpException();
        }

        $translation = new KnowbaseItemTranslation();
        $exists = $translation->getFromDBByCrit([
            'knowbaseitems_id' => $id,
            'language' => $language,
        ]);

        if (!$exists) {
            return new JsonResponse([
                'success' => false,
                'message' => __('Translation not found'),
            ], Response::HTTP_NOT_FOUND);
        }

        $this->delete(KnowbaseItemTranslation::class, $translation->getID());

        return new JsonResponse([
            'success' => true,
            'message' => __('Translation deleted successfully'),
        ]);
    }
}
