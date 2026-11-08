<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

/**
 * @since 0.84
 */

$cipm = new CartridgeItem_PrinterModel();
if (isset($_POST["add"])) {
    try {
        $cipm->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if ($cipm->add($_POST)) {
        Event::log(
            $_POST["cartridgeitems_id"],
            "cartridges",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s associates a type'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
