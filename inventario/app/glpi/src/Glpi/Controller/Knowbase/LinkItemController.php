<?php

namespace Glpi\Controller\Knowbase;

use CommonDBTM;
use Glpi\Application\View\TemplateRenderer;
use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use KnowbaseItem_Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\json_decode;

final class LinkItemController extends AbstractController
{
    use CrudControllerTrait;

    #[Route(
        "/Knowbase/{id}/LinkItem",
        name: "knowbase_link_item",
        methods: ["POST"],
        requirements: ['id' => '\d+']
    )]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }

        $data = json_decode($request->getContent(), true);
        $itemtype = (string) ($data['itemtype'] ?? '');
        $items_id = (int) ($data['items_id'] ?? 0);

        if (
            $itemtype === ''
            || $items_id <= 0
            || !is_a($itemtype, CommonDBTM::class, true)
        ) {
            throw new BadRequestHttpException();
        }

        $link = $this->add(KnowbaseItem_Item::class, [
            'knowbaseitems_id' => $id,
            'itemtype'         => $itemtype,
            'items_id'         => $items_id,
        ]);

        $linked_item = new $itemtype();
        if (!$linked_item->getFromDB($items_id)) {
            throw new BadRequestHttpException();
        }

        $icon_and_color = KnowbaseItem::getRelatedItemIconAndColor($itemtype);

        $item_data = [
            'id'          => $link->getID(),
            'items_id'    => $items_id,
            'itemtype'    => $itemtype,
            'name'        => $linked_item->getName(),
            'link_url'    => $linked_item->getLinkURL(),
            'type_name'   => $itemtype::getTypeName(1),
            'icon_class'  => $icon_and_color['icon_class'],
            'color_class' => $icon_and_color['color_class'],
        ];

        $html = TemplateRenderer::getInstance()->render(
            'pages/tools/kb/related_item_badge.html.twig',
            [
                'item'     => $item_data,
                'can_edit' => true,
            ]
        );

        return new JsonResponse(['item' => ['html' => $html]]);
    }
}
