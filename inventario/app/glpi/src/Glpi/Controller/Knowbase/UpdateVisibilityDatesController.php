<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class UpdateVisibilityDatesController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/{id}/UpdateVisibilityDates",
        name: "knowbase_update_visibility_dates",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        // Decode data
        $data = json_decode($request->getContent(), true);

        // Build update input
        $update_input = [];
        if (array_key_exists('begin_date', $data)) {
            $update_input['begin_date'] = $data['begin_date'] ?: null;
        }
        if (array_key_exists('end_date', $data)) {
            $update_input['end_date'] = $data['end_date'] ?: null;
        }
        if ($update_input === []) {
            return new JsonResponse(['error' => 'No fields provided'], 400);
        }

        // Execute update
        $this->update(
            KnowbaseItem::class,
            $id,
            $update_input,
        );
        return new JsonResponse(['success' => true]);
    }
}
