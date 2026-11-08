<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\RichText\RichText;
use KnowbaseItem;
use KnowbaseItemTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetTranslationContentController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/KnowbaseItem/{knowbaseitems_id}/Translation/{language}",
        name: "knowbaseitem_translation_content",
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
        if ($exists) {
            $translation = $this->read(KnowbaseItemTranslation::class, $translation->getID());
            return new JsonResponse([
                'exists' => true,
                'name' => $translation->fields['name'],
                'answer' => RichText::getSafeHtml($translation->fields['answer']),
            ]);
        }

        return new JsonResponse([
            'exists' => false,
            'name' => '',
            'answer' => '',
        ]);
    }
}
