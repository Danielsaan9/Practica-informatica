<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Knowbase\CommentsThread;
use KnowbaseItem;
use KnowbaseItem_Comment;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class AddCommentController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/{id}/AddComment",
        name: "knowbase_article_add_comment",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Parse kb id
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }

        // Parse content
        $content = $data['content'] ?? '';
        if ($content === "") {
            throw new BadRequestHttpException();
        }

        // Parse optional parent comment id
        $parent_comment_id = $data['parent_comment_id'] ?? null;

        $input = [
            'knowbaseitems_id'  => $id,
            'comment'           => $content,
            'parent_comment_id' => $parent_comment_id,
        ];

        // Anchors only apply to a new thread, never a reply.
        if ($parent_comment_id === null && !empty($data['anchor_exact'])) {
            $input['anchor_prefix']     = (string) ($data['anchor_prefix'] ?? '');
            $input['anchor_exact']      = (string) $data['anchor_exact'];
            $input['anchor_suffix']     = (string) ($data['anchor_suffix'] ?? '');
            $input['anchor_occurrence'] = (int) ($data['anchor_occurrence'] ?? 0);

            if (!(new KnowbaseItem_Comment())->hasValidAnchorLengths($input)) {
                throw new BadRequestHttpException();
            }
        }

        $comment = $this->add(KnowbaseItem_Comment::class, $input);

        if ($parent_comment_id === null) {
            // Comment has no parent, render a new thread
            return $this->render('pages/tools/kb/sidepanel/comments_thread.html.twig', [
                'thread' => new CommentsThread([$comment]),
                'users'  => [Session::getLoginUserID() => Session::getCurrentUser()],
            ]);
        } else {
            // Render new comment
            return $this->render('pages/tools/kb/sidepanel/comment.html.twig', [
                'comment_id'    => $comment->getID(),
                'user'          => Session::getCurrentUser(),
                'date_creation' => $comment->fields['date_creation'],
                'comment'       => $comment->fields['comment'],
                'can_edit'      => true,
                'can_purge'     => true,
                'anchor_quote'  => null,
            ]);
        }
    }
}
