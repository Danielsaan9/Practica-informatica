<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

if (isset($_POST["add"])) {
    Item_Devices::addDevicesFromPOST($_POST);
    Html::back();
} elseif (isset($_POST["updateall"])) {
    Item_Devices::updateAll($_POST);
    Html::back();
}

throw new BadRequestHttpException();
