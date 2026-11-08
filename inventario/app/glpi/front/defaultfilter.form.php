<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(DefaultFilter::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$defaultfilter = new DefaultFilter();
if (isset($_POST["add"])) {
    $defaultfilter->check(-1, CREATE, $_POST);

    if ($newID = $defaultfilter->add($_POST)) {
        Event::log(
            $newID,
            "defaultfilters",
            4,
            "defaultfilter",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($defaultfilter->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $defaultfilter->check($_POST["id"], PURGE);

    if ($defaultfilter->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            "defaultfilters",
            4,
            "defaultfilter",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $defaultfilter->redirectToList();
} elseif (isset($_POST["update"])) {
    $defaultfilter->check($_POST["id"], UPDATE);

    if ($defaultfilter->update($_POST)) {
        Event::log(
            $_POST["id"],
            "defaultfilters",
            4,
            "defaultfilter",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["config", "commondropdown", "DefaultFilter"];
    DefaultFilter::displayFullPageForItem($_GET["id"], $menus);
}
