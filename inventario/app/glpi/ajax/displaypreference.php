<?php

use Glpi\Exception\Http\BadRequestHttpException;

Html::header_nocache();

$setupdisplay = new DisplayPreference();

DisplayPreference::checkCrudItem($_POST);

if (isset($_POST["activate"])) {
    $setupdisplay->activatePerso($_POST);
} elseif (isset($_POST["disable"])) {
    if ($_POST['users_id'] == Session::getLoginUserID()) {
        $setupdisplay->deleteByCriteria(['users_id' => $_POST['users_id'],
            'itemtype' => $_POST['itemtype'],
        ]);
    }
} elseif (isset($_POST['action']) && $_POST['action'] === 'update_order') {
    if (!isset($_POST['itemtype'], $_POST['users_id'], $_POST['opts'])) {
        throw new BadRequestHttpException();
    }
    $setupdisplay->updateOrder(
        $_POST['itemtype'],
        $_POST['users_id'],
        $_POST['opts'],
        $_POST['interface'] ?? 'central'
    );
} else {
    throw new BadRequestHttpException();
}
