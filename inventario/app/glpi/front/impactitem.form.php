<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

$impact_item = new ImpactItem();

if (isset($_POST["update"])) {
    $id = $_POST["id"] ?? 0;

    // Can't update, id is missing
    if ($id === 0) {
        throw new BadRequestHttpException("Can't update the target impact item, id is missing");
    }

    // Load item and check rights
    $impact_item->getFromDB($id);
    Session::checkRight($impact_item->fields['itemtype']::$rightname, UPDATE);

    // Update item and back
    $impact_item->update($_POST);
    Html::redirect(Html::getBackUrl() . "#list");
}
