<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class ToggleFieldController extends AbstractController
{
    use CrudControllerTrait;

    private const ALLOWED_FIELDS = ['is_faq', 'is_recursive'];

    #[Route(
        "/Knowbase/{id}/ToggleField",
        name: "knowbase_toggle_field",
        methods: ["POST"],
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, Request $request): Response
    {
        // Decode submitted data
        $data = json_decode($request->getContent(), true);
        $field = $data['field'] ?? null;
        $value = $data['value'] ?? null;

        // Validate parameters
        if ($field === null || $value === null) {
            throw new BadRequestHttpException();
        }
        if (!in_array($field, self::ALLOWED_FIELDS, true)) {
            throw new BadRequestHttpException();
        }

        // Update item
        $this->update(
            KnowbaseItem::class,
            $id,
            [$field => $value ? 1 : 0],
        );

        return new Response(); // OK
    }
}
