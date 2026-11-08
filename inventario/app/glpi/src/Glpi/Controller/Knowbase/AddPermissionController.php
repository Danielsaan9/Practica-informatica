<?php

namespace Glpi\Controller\Knowbase;

use Entity;
use Entity_KnowbaseItem;
use Glpi\Application\View\TemplateRenderer;
use Glpi\Controller\AbstractController;
use Glpi\Controller\CrudControllerTrait;
use Glpi\Event;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Knowbase\SidePanel\PermissionsRenderer;
use Group;
use Group_KnowbaseItem;
use KnowbaseItem;
use KnowbaseItem_Profile;
use KnowbaseItem_User;
use Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use User;

final class AddPermissionController extends AbstractController
{
    use CrudControllerTrait;

    #[Route('/Knowbase/AddPermission', name: 'glpi_knowbaseitem_add_permission', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $id = $request->request->getInt('knowbaseitems_id');
        $kb = new KnowbaseItem();
        if (!$kb->can($id, UPDATE)) {
            throw new AccessDeniedHttpException();
        }

        $input = ['knowbaseitems_id' => $id];
        $type = $request->request->getString('_type');
        if (empty($type)) {
            throw new BadRequestHttpException();
        }

        if ($type === User::class) {
            $input['users_id'] = $request->request->getInt('users_id');
            $class             = KnowbaseItem_User::class;
        } elseif ($type === Group::class) {
            $input['groups_id']    = $request->request->getInt('groups_id');
            $input['entities_id']  = $request->request->getInt('entities_id');
            $input['is_recursive'] = $request->request->getBoolean('is_recursive');
            $class                 = Group_KnowbaseItem::class;
        } elseif ($type === Profile::class) {
            $input['profiles_id']  = $request->request->getInt('profiles_id');
            $input['entities_id']  = $request->request->getInt('entities_id');
            $input['is_recursive'] = $request->request->getBoolean('is_recursive');
            $class                 = KnowbaseItem_Profile::class;
        } elseif ($type === Entity::class) {
            $input['entities_id']  = $request->request->getInt('entities_id');
            $input['is_recursive'] = $request->request->getBoolean('is_recursive');
            $class                 = Entity_KnowbaseItem::class;
        } else {
            throw new BadRequestHttpException();
        }

        if (isset($input['entities_id']) && $input['entities_id'] == -1) {
            $input['entities_id'] = null;
            $input['no_entity_restriction'] = 1;
        }

        $relation = $this->add($class, $input);
        Event::log(
            $id,
            "knowbaseitem",
            4,
            "tools",
            sprintf(__('%s adds a target'), $_SESSION["glpiname"])
        );

        $renderer = new PermissionsRenderer();
        $entry = $renderer->buildEntry($class, $relation->getID());
        if ($entry === null) {
            throw new BadRequestHttpException();
        }

        $html = TemplateRenderer::getInstance()->render(
            'pages/tools/kb/permission_entry.html.twig',
            [
                'entry'    => $entry,
                'can_edit' => true,
            ]
        );

        return new JsonResponse([
            'html' => $html,
            'name' => $entry['name'],
        ]);
    }
}
