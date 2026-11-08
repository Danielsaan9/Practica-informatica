<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(RefusedEquipment::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$refusedequipment = new RefusedEquipment();
if (isset($_POST["purge"])) {
    $refusedequipment->check($_POST["id"], PURGE);
    if ($refusedequipment->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            "refusedequipment",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $refusedequipment->redirectToList();
} elseif (isset($_POST["update"])) {
    $refusedequipment->check($_POST["id"], UPDATE);
    $refusedequipment->update($_POST);
    Event::log(
        $_POST["id"],
        "refusedequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["admin", "glpi\inventory\inventory", "RefusedEquipment"];
    RefusedEquipment::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true",
    ]);
}
