<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRightsOr(Consumable::$rightname, [READ, READ_ASSIGNED]);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$constype = new ConsumableItem();

if (isset($_POST["add"])) {
    $constype->check(-1, CREATE, $_POST);

    if ($newID = $constype->add($_POST)) {
        Event::log(
            $newID,
            "consumableitems",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($constype->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $constype->check($_POST["id"], DELETE);

    if ($constype->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "consumableitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $constype->redirectToList();
} elseif (isset($_POST["restore"])) {
    $constype->check($_POST["id"], DELETE);

    if ($constype->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "consumableitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $constype->redirectToList();
} elseif (isset($_POST["purge"])) {
    $constype->check($_POST["id"], PURGE);

    if ($constype->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            "consumableitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $constype->redirectToList();
} elseif (isset($_POST["update"])) {
    $constype->check($_POST["id"], UPDATE);

    if ($constype->update($_POST)) {
        Event::log(
            $_POST["id"],
            "consumableitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["assets", "consumableitem"];
    ConsumableItem::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true",
    ]);
}
