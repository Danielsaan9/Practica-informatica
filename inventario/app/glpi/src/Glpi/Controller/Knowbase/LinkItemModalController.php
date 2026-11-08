<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use KnowbaseItem;
use KnowbaseItem_Item;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LinkItemModalController extends AbstractController
{
    #[Route(
        "/Knowbase/{id}/LinkItemModal",
        name: "knowbase_link_item_modal",
        requirements: ['id' => '\d+']
    )]
    public function __invoke(int $id): Response
    {
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }
        if (!$kb->can($id, UPDATE)) {
            throw new AccessDeniedHttpException();
        }

        $used_items = [];
        foreach (KnowbaseItem_Item::getItems($kb, 0, 0) as $data) {
            $used_items[$data['itemtype']][] = $data['items_id'];
        }

        return $this->render(
            'pages/tools/kb/knowbaseitem_item.html.twig',
            [
                'item' => $kb,
                'visibility_condition' => [],
                'used_knowbase_items' => [],
                'used_items' => $used_items,
            ]
        );
    }
}
