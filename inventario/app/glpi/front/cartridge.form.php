<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRightsOr(Cartridge::$rightname, [READ, READ_ASSIGNED]);

$cart    = new Cartridge();
$cartype = new CartridgeItem();

if (isset($_POST["add"])) {
    $cartype->check($_POST["cartridgeitems_id"], CREATE);

    for ($i = 0; $i < $_POST["to_add"]; $i++) {
        unset($cart->fields["id"]);
        $cart->add($_POST);
    }
    Event::log(
        $_POST["cartridgeitems_id"],
        "cartridgeitems",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s adds cartridges'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["purge"])) {
    $cartype->check($_POST["cartridgeitems_id"], PURGE);

    if ($cart->delete($_POST, true)) {
        Event::log(
            $_POST["cartridgeitems_id"],
            "cartridgeitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cartridge'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["install"])) {
    if ($_POST["cartridgeitems_id"]) {
        $cartype->check($_POST["cartridgeitems_id"], UPDATE);
        for ($i = 0; $i < $_POST["nbcart"]; $i++) {
            if ($cart->install($_POST["printers_id"], $_POST["cartridgeitems_id"])) {
                Event::log(
                    $_POST["printers_id"],
                    "printers",
                    5,
                    "inventory",
                    //TRANS: %s is the user login
                    sprintf(__('%s installs a cartridge'), $_SESSION["glpiname"])
                );
            }
        }
    }
    Html::redirect(Printer::getFormURLWithID($_POST["printers_id"]));
} elseif (isset($_POST["update"])) {
    $cart->check($_POST["id"], UPDATE);

    if ($cart->update($_POST)) {
        Event::log(
            $_POST["printers_id"],
            "printers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cartridge'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    Html::back();
}
