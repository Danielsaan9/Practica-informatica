<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class UpdateServiceCatalogController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/{id}/UpdateServiceCatalog",
        name: "knowbase_item_update_service_catalog",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // When the service catalog is disabled, the fieldset is disabled in the
        // browser, so textarea/select are not submitted so only the hidden inputs
        // (id, is_pinned) and the toggle (show_in_service_catalog) are sent.
        $show_in_catalog = isset($data['show_in_service_catalog'])
            && $data['show_in_service_catalog'] != 0
        ;

        if ($show_in_catalog) {
            $this->validateInputHasExactKeys($data, [
                'description',
                'forms_categories_id',
                'id',
                'is_pinned',
                'show_in_service_catalog',
            ]);
        } else {
            $this->validateInputHasExactKeys($data, [
                'id',
                'show_in_service_catalog',
            ]);
        }

        $this->update(KnowbaseItem::class, $id, $data);
        return new Response(); // OK
    }
}
