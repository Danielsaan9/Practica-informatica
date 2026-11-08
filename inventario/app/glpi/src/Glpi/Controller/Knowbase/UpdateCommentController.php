<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem_Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class UpdateCommentController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/UpdateComment/{id}",
        name: "knowbase_update_comment",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Get submitted comment
        $content = $data['content'] ?? '';
        if (empty($content)) {
            throw new BadRequestHttpException();
        }

        // Update item
        $comment = $this->update(
            KnowbaseItem_Comment::class,
            $id,
            ['comment' => $content],
        );

        return new JsonResponse([
            'success' => true,
            'comment' => $comment->fields['comment'],
        ]);
    }
}
