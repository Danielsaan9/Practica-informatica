<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();
$npv = new NetworkPort_Vlan();
if (isset($_POST["add"])) {
    $npv->check(-1, UPDATE, $_POST);

    if (isset($_POST["vlans_id"]) && ($_POST["vlans_id"] > 0)) {
        $npv->assignVlan(
            $_POST["networkports_id"],
            $_POST["vlans_id"],
            (isset($_POST['tagged']) ? ((int) $_POST['tagged']) : 0)
        );
        Event::log(
            0,
            "networkport",
            5,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s associates a VLAN to a network port'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
