<?php

namespace Glpi\Controller\ItemType\Form;

use CommonDBTM;
use Glpi\Controller\GenericFormController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\RedirectResponse;
use Glpi\Routing\Attribute\ItemtypeFormRoute;
use Plug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlugFormController extends GenericFormController
{
    #[ItemtypeFormRoute(Plug::class)]
    public function __invoke(Request $request): Response
    {
        global $CFG_GLPI;

        if ($request->request->has('add_several')) {
            $main_itemtype = $request->request->getString('itemtype_main');
            $main_item_id  = $request->request->getInt('items_id_main');

            if (!\in_array($main_itemtype, $CFG_GLPI['plug_types'], true)) {
                throw new BadRequestHttpException();
            }

            $main_item = \getItemForItemtype($main_itemtype);
            if (!($main_item instanceof CommonDBTM) || !$main_item->getFromDB($main_item_id)) {
                throw new BadRequestHttpException();
            }

            $base_input = [
                'itemtype_main'     => $main_itemtype,
                'items_id_main'     => $main_item_id,
                'entities_id'       => $main_item->getEntityID(),
                'is_recursive'      => $main_item->isRecursive(),
            ];

            $plug = new Plug();
            if (!$plug->can(-1, CREATE, $base_input)) {
                throw new AccessDeniedHttpException();
            }

            $name = $request->request->get('name');
            for ($i = 0; $i < $request->request->getInt('number'); $i++) {
                $input = $base_input + [
                    'name' => $name . " - " . ($i + 1),
                ];
                $plug->add($input);
            }
            return new RedirectResponse($main_item->getLinkURL());
        }

        if ($request->request->has('purge')) {
            $plug = new Plug();
            if ($plug->getFromDB($request->request->getInt('id', -1))) {
                // load main item to redirect after purge
                $main_item = $plug->getOnePeer(0);
            }
        }

        // Handle action using the generic controller
        $request->attributes->set('class', Plug::class);
        $response = parent::__invoke($request);

        if (isset($main_item) && $main_item instanceof CommonDBTM) {
            return new RedirectResponse($main_item->getLinkURL());
        }

        return $response;
    }
}
