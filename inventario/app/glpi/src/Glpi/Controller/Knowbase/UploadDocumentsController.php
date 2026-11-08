<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Application\View\TemplateRenderer;
use Glpi\Controller\AbstractDocumentUploadController;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class UploadDocumentsController extends AbstractDocumentUploadController
{
    #[Route(
        "/Knowbase/{id}/UploadDocuments",
        name: "knowbase_upload_documents",
        methods: ["POST"],
        requirements: ['id' => '\d+']
    )]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        // Load target KB item
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }

        // Read parameters
        $data  = json_decode($request->getContent(), true);
        $files = $data['files'] ?? [];
        if (!is_array($files) || count($files) === 0) {
            throw new BadRequestHttpException();
        }

        $result = [];
        $twig = TemplateRenderer::getInstance();

        foreach ($this->createDocuments(
            files: $files,
            itemtype: KnowbaseItem::class,
            items_id: $id,
            entities_id: $kb->getEntityID(),
            is_recursive: $kb->isRecursive(),
        ) as $doc) {
            // Compute dynamic styles based on file extension
            $styles = KnowbaseItem::getDocumentIconAndColor($doc['extension']);
            $doc['icon_class']  = $styles['icon_class'];
            $doc['color_class'] = $styles['color_class'];

            // Render badge
            $result[] = [
                'html' => $twig->render('pages/tools/kb/document_badge.html.twig', [
                    'doc'      => $doc,
                    'can_edit' => true,
                ]),
            ];
        }

        return new JsonResponse(['documents' => $result]);
    }
}
