<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

use Glpi\Event;

Session::checkRight(QueuedNotification::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$queuednotification = new QueuedNotification();

if (isset($_POST["delete"])) {
    $queuednotification->check($_POST["id"], DELETE);
    $queuednotification->delete($_POST);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} elseif (isset($_POST["restore"])) {
    $queuednotification->check($_POST["id"], DELETE);
    $queuednotification->restore($_POST);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} elseif (isset($_POST["purge"])) {
    $queuednotification->check($_POST["id"], PURGE);
    $queuednotification->delete($_POST, true);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} else {
    $menus = ["admin", "queuednotification"];
    QueuedNotification::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
