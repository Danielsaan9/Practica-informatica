<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();

$ien = new Item_Enclosure();
$enclosure = new Enclosure();

if (isset($_POST['update'])) {
    $ien->check($_POST['id'], UPDATE);
    //update existing relation
    if ($ien->update($_POST)) {
        $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    } else {
        $url = $ien->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['add'])) {
    $ien->check(-1, CREATE, $_POST);
    $ien->add($_POST);
    $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    Html::redirect($url);
} elseif (isset($_POST['purge'])) {
    $ien->check($_POST['id'], PURGE);
    $ien->delete($_POST, true);
    $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    Html::redirect($url);
}

if (!isset($_REQUEST['enclosure']) && !isset($_REQUEST['id'])) {
    throw new BadRequestHttpException();
}

$params = [];
if (isset($_REQUEST['id'])) {
    $params['id'] = $_REQUEST['id'];
} else {
    $params = [
        'enclosures_id'   => $_REQUEST['enclosure'],
    ];
}

$menus = ["assets", Enclosure::class];
Item_Enclosure::displayFullPageForItem($_REQUEST['id'] ?? 0, $menus, $params);
