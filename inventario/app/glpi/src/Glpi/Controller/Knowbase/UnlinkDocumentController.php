<?php

namespace Glpi\Controller\Knowbase;

use Document_Item;
use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnlinkDocumentController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/UnlinkDocument/{id}",
        name: "knowbase_unlink_document",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id): Response
    {
        $this->purge(Document_Item::class, $id);
        return new Response(); // OK
    }
}
