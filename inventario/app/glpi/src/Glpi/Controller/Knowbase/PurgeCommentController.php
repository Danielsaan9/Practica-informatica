<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use KnowbaseItem_Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurgeCommentController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/PurgeComment/{id}",
        name: "knowbase_purge_comment",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id): Response
    {
        $this->purge(KnowbaseItem_Comment::class, $id);
        return new Response(); // OK
    }
}
