<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;

Session::checkCentralAccess();

$ira = new Item_Rack();
$rack = new Rack();

if (isset($_POST['update'])) {
    $ira->check($_POST['id'], UPDATE);
    //update existing relation
    if ($ira->update($_POST)) {
        $url = $rack->getFormURLWithID($_POST['racks_id']);
    } else {
        $url = $ira->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['add'])) {
    $ira->check(-1, CREATE, $_POST);
    $ira->add($_POST);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
} elseif (isset($_POST['purge'])) {
    $ira->check($_POST['id'], PURGE);
    $ira->delete($_POST, true);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
}

if (!isset($_GET['unit']) && !isset($_GET['orientation']) && !isset($_GET['rack']) && !isset($_GET['id'])) {
    throw new BadRequestHttpException();
}

$params = [];
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
} else {
    $params = [
        'racks_id'     => $_GET['racks_id'],
        'orientation'  => $_GET['orientation'],
        'position'     => $_GET['position'],
    ];
    if (isset($_GET['_onlypdu'])) {
        $params['_onlypdu'] = $_GET['_onlypdu'];
    }
}
$ajax = isset($_REQUEST['ajax']);

if ($ajax) {
    $item = new Item_Rack();
    $id = $params['id'] ?? 0;
    if ($id > 0 && !$item->getFromDB($params['id'])) {
        throw new NotFoundHttpException();
    }
    $item->showForm($id, $params + ['no_header' => true]);
} else {
    $menus = ["assets", "rack"];
    Item_Rack::displayFullPageForItem($params['id'] ?? 0, $menus, $params);
}
