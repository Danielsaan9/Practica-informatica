<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

Session::checkCentralAccess();

$iapp = new Appliance_Item();
$app = new Appliance();

if (isset($_POST['update'])) {
    $iapp->check($_POST['id'], UPDATE);
    //update existing relation
    if ($iapp->update($_POST)) {
        $url = $app->getFormURLWithID($_POST['appliances_id']);
    } else {
        $url = $iapp->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} elseif (isset($_POST['add'])) {
    try {
        $iapp->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    $iapp->add($_POST);
    Html::back();
} elseif (isset($_POST['purge'])) {
    $iapp->check($_POST['id'], PURGE);
    $iapp->delete($_POST, true);
    $url = $app->getFormURLWithID($_POST['appliances_id']);
    Html::redirect($url);
}

throw new BadRequestHttpException();
