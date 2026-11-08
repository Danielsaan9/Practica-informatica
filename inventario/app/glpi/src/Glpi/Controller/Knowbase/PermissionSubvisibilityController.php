<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Group;
use KnowbaseItem;
use Profile;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PermissionSubvisibilityController extends AbstractController
{
    #[Route(
        "/Knowbase/PermissionSubvisibility",
        name: "knowbase_permission_subvisibility",
        methods: 'POST',
    )]
    public function __invoke(Request $request): Response
    {
        // Get mandatory param
        $type = $request->request->getString('type');
        if (!in_array($type, [Group::class, Profile::class], true)) {
            throw new BadRequestHttpException();
        }

        // If the user can't update the KB, he should problably not be able
        // to see this dropdown at all.
        if (!KnowbaseItem::canUpdate()) {
            throw new AccessDeniedHttpException();
        }

        $rand = mt_rand();
        $entity_dropdown_options = [
            'value' => $_SESSION['glpiactive_entity'],
            'name'  => 'entities_id',
            'width' => '100%',
            'rand'  => $rand,
        ];

        if (Session::canViewAllEntities()) {
            $entity_dropdown_options['toadd'] = [-1 => __('No restriction')];
        }

        return $this->render('pages/tools/kb/permission_subvisibility.html.twig', [
            'rand'                    => $rand,
            'entity_dropdown_options' => $entity_dropdown_options,
        ]);
    }
}
