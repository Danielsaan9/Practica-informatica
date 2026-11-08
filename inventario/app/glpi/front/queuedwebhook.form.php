<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

use Glpi\Event;

Session::checkRight(QueuedWebhook::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$queuedwebhook = new QueuedWebhook();

if (isset($_POST["delete"])) {
    $queuedwebhook->check($_POST["id"], DELETE);
    if ($queuedwebhook->delete($_POST)) {
        Event::log(
            $_POST["id"],
            QueuedWebhook::class,
            4,
            "webhook",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $queuedwebhook->redirectToList();
} elseif (isset($_POST["restore"])) {
    $queuedwebhook->check($_POST["id"], DELETE);
    if ($queuedwebhook->restore($_POST)) {
        Event::log(
            $_POST["id"],
            QueuedWebhook::class,
            4,
            "webhook",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }

    $queuedwebhook->redirectToList();
} elseif (isset($_POST["purge"])) {
    $queuedwebhook->check($_POST["id"], PURGE);
    if ($queuedwebhook->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            QueuedWebhook::class,
            4,
            "webhook",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }

    $queuedwebhook->redirectToList();
} else {
    $menus = ["config", "webhook"];
    QueuedWebhook::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
