<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();

$app_item_rel = new Appliance_Item_Relation();

if (isset($_POST['add'])) {
    $app_item_rel->check(-1, CREATE, $_POST);
    $app_item_rel->add($_POST);
    Html::back();
} elseif (isset($_POST['purge'])) {
    $app_item_rel->check($_POST['id'], PURGE);
    $app_item_rel->delete($_POST, true);
    Html::back();
}

throw new BadRequestHttpException();
