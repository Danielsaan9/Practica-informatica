<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use KnowbaseItem_Revision;
use Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RevertRevisionController extends AbstractController
{
    #[Route(
        "/Knowbase/{id}/RevertTo/{revision_id}",
        name: "knowbase_article_revert_revision",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
            'revision_id' => '\d+',
        ]
    )]
    public function __invoke(int $id, int $revision_id): Response
    {
        // Load the KnowbaseItem
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }

        // Check permissions
        if (!$kb->can($id, UPDATE)) {
            throw new AccessDeniedHttpException();
        }

        // Verify the revision exists and belongs to this KB item
        $revision = KnowbaseItem_Revision::getById($revision_id);
        if (!$revision || (int) $revision->fields['knowbaseitems_id'] !== $id) {
            throw new BadRequestHttpException();
        }

        // Perform the revert
        if (!$kb->revertTo($revision_id)) {
            return new JsonResponse([
                'success' => false,
                'message' => __("Failed to restore the revision."),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Session::addMessageAfterRedirect(__s("Article restored successfully."));

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
