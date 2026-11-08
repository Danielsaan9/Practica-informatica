<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\RichText\RichText;
use KnowbaseItem;
use KnowbaseItemTranslation;
use Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class SaveTranslationController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/KnowbaseItem/{knowbaseitems_id}/Translation",
        name: "knowbaseitem_translation_save",
        methods: ["POST"],
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

        if (!$kbitem->can($id, UPDATE)) {
            throw new AccessDeniedHttpException();
        }

        $data = json_decode($request->getContent(), true);
        $language = $data['language'] ?? null;
        $answer = $data['answer'] ?? null;
        $name = $data['name'] ?? null;

        if ($language === null || $answer === null) {
            return new JsonResponse([
                'success' => false,
                'message' => __('Missing required fields'),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Empty-check on the rendered plaintext first (avoids a useless sanitize on
        // empty content). getTextFromHtml runs renderAllAsText, so video-only
        // translations (placeholder div with no inline text) are not rejected.
        if (trim(RichText::getTextFromHtml($answer, false)) === '') {
            return new JsonResponse([
                'success' => false,
                'message' => __('Content cannot be empty'),
            ], Response::HTTP_BAD_REQUEST);
        }

        // KB video placeholders are preserved by the sanitizer (inert data-video-* attributes).
        $answer = RichText::getSafeHtml($answer);

        // Make sure title is not empty if specified
        if ($name !== null) {
            $name = strip_tags(trim($name));
            if ($name === '') {
                return new JsonResponse([
                    'success' => false,
                    'message' => __('Title cannot be empty'),
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Try to find a row for the given language
        $translation = new KnowbaseItemTranslation();
        $exists = $translation->getFromDBByCrit([
            'knowbaseitems_id' => $id,
            'language' => $language,
        ]);

        if ($exists) {
            $update_data = [
                'id' => $translation->getID(),
                'answer' => $answer,
            ];
            if ($name !== null) {
                $update_data['name'] = $name;
            }
            $this->update(
                KnowbaseItemTranslation::class,
                $translation->getID(),
                $update_data,
            );
        } else {
            $input = [
                'knowbaseitems_id' => $id,
                'language' => $language,
                'answer' => $answer,
                'name' => $name ?? '',
                'users_id' => Session::getLoginUserID(),
            ];
            $this->add(KnowbaseItemTranslation::class, $input);
        }

        return new JsonResponse([
            'success' => true,
            'message' => __('Translation saved successfully'),
        ]);
    }
}
