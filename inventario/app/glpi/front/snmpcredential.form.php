<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(SNMPCredential::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$cred = new SNMPCredential();
if (isset($_POST["add"])) {
    $cred->check(-1, CREATE, $_POST);
    if ($newID = $cred->add($_POST)) {
        Event::log(
            $newID,
            "snmpcredential",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );

        if ($_SESSION['glpibackcreated']) {
            Html::redirect($cred->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $cred->check($_POST["id"], DELETE);
    if ($cred->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "snmpcredential",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $cred->redirectToList();
} elseif (isset($_POST["restore"])) {
    $cred->check($_POST["id"], DELETE);
    if ($cred->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "snmpcredential",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $cred->redirectToList();
} elseif (isset($_POST["purge"])) {
    $cred->check($_POST["id"], PURGE);
    if ($cred->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            "snmpcredential",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $cred->redirectToList();
} elseif (isset($_POST["update"])) {
    $cred->check($_POST["id"], UPDATE);
    $cred->update($_POST);
    Event::log(
        $_POST["id"],
        "snmpcredential",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["admin", "glpi\inventory\inventory", "SNMPCredential"];
    SNMPCredential::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true",
    ]);
}
