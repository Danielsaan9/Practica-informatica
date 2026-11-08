<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(Datacenter::$rightname, READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$datacenter = new Datacenter();

if (isset($_POST["add"])) {
    $datacenter->check(-1, CREATE, $_POST);

    if ($newID = $datacenter->add($_POST)) {
        Event::log(
            $newID,
            "datacenters",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($datacenter->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $datacenter->check($_POST["id"], DELETE);
    $datacenter->delete($_POST);

    Event::log(
        $_POST["id"],
        "datacenters",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $datacenter->redirectToList();
} elseif (isset($_POST["restore"])) {
    $datacenter->check($_POST["id"], DELETE);

    $datacenter->restore($_POST);
    Event::log(
        $_POST["id"],
        "datacenters",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $datacenter->redirectToList();
} elseif (isset($_POST["purge"])) {
    $datacenter->check($_POST["id"], PURGE);

    $datacenter->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "datacenters",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $datacenter->redirectToList();
} elseif (isset($_POST["update"])) {
    $datacenter->check($_POST["id"], UPDATE);

    $datacenter->update($_POST);
    Event::log(
        $_POST["id"],
        "datacenters",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["management", "datacenter"];
    Datacenter::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true",
    ]);
}
