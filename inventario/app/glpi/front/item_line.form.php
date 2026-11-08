<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

Session::checkCentralAccess();

$item_line = new Item_Line();
$line = new Line();

if (isset($_POST['update'])) {
    $item_line->check($_POST['id'], UPDATE);
    //update existing relation
    if ($item_line->update($_POST)) {
        $url = $line->getFormURLWithID($_POST['lines_id']);
    } else {
        $url = $item_line->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['add'])) {
    try {
        $item_line->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }
    $item_line->check(-1, CREATE, $_POST);
    $item_line->add($_POST);
    if (isset($_POST['_from']) && $_POST['_from'] === 'item') {
        $url = $_POST['itemtype']::getFormURLWithID($_POST['items_id']);
    } else {
        $url = $line->getFormURLWithID($_POST['lines_id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['purge'])) {
    $item_line->check($_POST['id'], PURGE);
    $item_line->delete($_POST, true);
    if (isset($_POST['_from']) && $_POST['_from'] === 'item') {
        $url = $_POST['itemtype']::getFormURLWithID($_POST['items_id']);
    } else {
        $url = $line->getFormURLWithID($_POST['lines_id']);
    }
    Html::redirect($url);
}

if (!isset($_REQUEST['line']) && !isset($_REQUEST['id']) && !isset($_REQUEST['items_id'])) {
    throw new BadRequestHttpException();
}

$params = [];
if (isset($_REQUEST['id'])) {
    $params['id'] = $_REQUEST['id'];
} elseif (isset($_REQUEST['line'])) {
    $params = [
        'lines_id'  => $_REQUEST['line'],
        '_from'     => 'line',
    ];
} elseif (isset($_REQUEST['items_id'])) {
    $params = [
        'itemtype'  => $_REQUEST['itemtype'],
        'items_id'  => $_REQUEST['items_id'],
        '_from'     => 'item',
    ];
}

throw new BadRequestHttpException();
