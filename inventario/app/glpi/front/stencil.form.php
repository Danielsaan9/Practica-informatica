<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;

if (isset($_POST['id'])) {
    $stencil = Stencil::getStencilFromID($_POST['id']);

    if (!$stencil) {
        throw new NotFoundHttpException('Stencil not found');
    }

    $stencil->check($_POST['id'], READ);

    if (isset($_POST['purge'])) {
        $stencil->check($_POST['id'], PURGE);
        $stencil->delete($_POST, true);
    }
} elseif (isset($_POST['itemtype'])) {
    // This code block retrieves an item based on the itemtype and items_id parameters.
    // The itemtype and items_id parameters are necessary because the Stencil class targets multiple objects of different types
    $item = getItemForItemtype($_POST['itemtype']);
    if (!$item || !$item->canView()) {
        throw new AccessDeniedHttpException();
    }

    if ($item->getFromDB($_POST['items_id'])) {
        $stencil = Stencil::getStencilFromItem($item);
        if (isset($_POST['add'])) {
            $stencil->check(-1, CREATE, $_POST);
            $stencil->add($_POST);
        }
    }
}

Html::back();
