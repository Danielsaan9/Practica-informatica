<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

$pra  = new PDU_Rack();
$rack = new Rack();

if (isset($_POST['update'])) {
    $pra->check($_POST['id'], UPDATE);
    //update existing relation
    if ($pra->update($_POST)) {
        $url = $rack->getFormURLWithID($_POST['racks_id']);
    } else {
        $url = $pra->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['add'])) {
    $pra->check(-1, CREATE, $_POST);
    $pra->add($_POST);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
} elseif (isset($_POST['purge'])) {
    $pra->check($_POST['id'], PURGE);
    $pra->delete($_POST, true);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
}

$params = [];
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
} else {
    $params = [
        'racks_id'     => $_GET['racks_id'],
    ];
}

$_SESSION['glpilisturl'][PDU_Rack::class] = $rack->getSearchURL();

$ajax = isset($_REQUEST['ajax']);

if ($ajax) {
    $pra->showForm($params['id'] ?? 0, $params + ['no_header' => true]);
} else {
    $menus = ["assets", "rack"];
    PDU_Rack::displayFullPageForItem((int) ($_GET['id'] ?? 0), $menus, $params);
}
