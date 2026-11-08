<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(Notification::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$notification = new Notification();
if (isset($_POST["add"])) {
    $notification->check(-1, CREATE, $_POST);

    if ($newID = $notification->add($_POST)) {
        Event::log(
            $newID,
            "notifications",
            4,
            "notification",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($notification->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $notification->check($_POST["id"], PURGE);
    $notification->delete($_POST, true);

    Event::log(
        $_POST["id"],
        "notifications",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $notification->redirectToList();
} elseif (isset($_POST["update"])) {
    $notification->check($_POST["id"], UPDATE);

    $notification->update($_POST);
    Event::log(
        $_POST["id"],
        "notifications",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "notification", "Notification"];
    Notification::displayFullPageForItem($_GET["id"], $menus);
}
