<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use KnowbaseItem_Item;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnlinkItemController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/UnlinkItem/{id}",
        name: "knowbase_unlink_item",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id): Response
    {
        $this->purge(KnowbaseItem_Item::class, $id);
        return new Response(); // OK
    }
}
