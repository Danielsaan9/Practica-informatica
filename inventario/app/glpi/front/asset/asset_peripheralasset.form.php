<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Asset\Asset_PeripheralAsset;
use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

$relation = new Asset_PeripheralAsset();

if (isset($_POST['add'], $_POST['itemtype_asset'], $_POST['items_id_asset'], $_POST['itemtype_peripheral'], $_POST['items_id_peripheral'])) {
    $relation->check(-1, CREATE, $_POST);
    if ($relation->add($_POST)) {
        Event::log(
            $_POST['items_id_peripheral'],
            $_POST['itemtype_peripheral'],
            5,
            'inventory',
            //TRANS: %s is the user login
            sprintf(__('%s connects an item'), $_SESSION['glpiname'])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
