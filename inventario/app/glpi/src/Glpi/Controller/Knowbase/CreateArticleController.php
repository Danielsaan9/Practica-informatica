<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class CreateArticleController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/KnowbaseItem/Create",
        name: "knowbase_article_create",
        methods: ["POST"],
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            throw new BadRequestHttpException();
        }

        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') {
            throw new BadRequestHttpException();
        }

        $raw_parent_id = (int) ($data['knowbaseitems_id_parent'] ?? 0);
        $parent_id = KnowbaseItem::getReadablePrefilledParentId($raw_parent_id);

        $input = [
            'name'         => $name,
            'answer'       => '',
            'entities_id'  => Session::getActiveEntity(),
            '_parents'     => $parent_id !== null ? [$parent_id] : [],
        ];

        // Retry in the parent's entity; add() checks again, so other refusals stay a 403.
        $parent = $parent_id !== null ? KnowbaseItem::getById($parent_id) : false;
        if ($parent !== false && !(new KnowbaseItem())->can(-1, CREATE, $input)) {
            $input['entities_id']  = $parent->getEntityID();
            $input['is_recursive'] = (int) $parent->isRecursive();
        }

        $item = $this->add(KnowbaseItem::class, $input);

        return new JsonResponse([
            'id'  => (int) $item->getID(),
            'url' => KnowbaseItem::getFormURLWithID($item->getID()),
        ]);
    }
}
