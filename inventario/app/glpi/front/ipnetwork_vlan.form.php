<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();
$npv = new IPNetwork_Vlan();
if (isset($_POST["add"])) {
    $npv->check(-1, CREATE, $_POST);

    if (isset($_POST["vlans_id"]) && ($_POST["vlans_id"] > 0)) {
        $npv->assignVlan($_POST["ipnetworks_id"], $_POST["vlans_id"]);
        Event::log(
            0,
            "ipnetwork",
            5,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s associates a VLAN to a network port'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
