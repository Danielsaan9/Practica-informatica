<?php

namespace Glpi\Api\HL\Middleware;

use CommonDBTM;
use Glpi\Api\HL\Controller\AbstractController;

class CRUDRequestMiddleware extends AbstractMiddleware implements RequestMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        if (!$input->request->hasAttribute('itemtype')) {
            $next($input);
            return;
        }

        $specific_item = $input->request->hasAttribute('id');
        /** @var class-string<CommonDBTM> $itemtype */
        $itemtype = $input->request->getAttribute('itemtype');
        /** @var CommonDBTM $item */
        $item = getItemForItemtype($itemtype);
        if ($specific_item) {
            $items_id = $input->request->getAttribute('id');

            if ($item && !$item->getFromDB($items_id)) {
                $input->response = AbstractController::getNotFoundErrorResponse();
                return;
            }
            $input->request->setParameter('_item', $item);
        }

        $next($input);
    }
}
