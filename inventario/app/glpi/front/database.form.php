<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(Database::$rightname, READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET['databaseinstances_id'])) {
    $_GET['databaseinstances_id'] = '';
}

$database = new Database();

if (isset($_POST["add"])) {
    $database->check(-1, CREATE, $_POST);

    if ($newID = $database->add($_POST)) {
        Event::log(
            $newID,
            "database",
            4,
            "management",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($database->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $database->check($_POST["id"], DELETE);
    $database->delete($_POST);

    Event::log(
        $_POST["id"],
        "database",
        4,
        "management",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $database->redirectToList();
} elseif (isset($_POST["restore"])) {
    $database->check($_POST["id"], DELETE);

    $database->restore($_POST);
    Event::log(
        $_POST["id"],
        "database",
        4,
        "management",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $database->redirectToList();
} elseif (isset($_POST["purge"])) {
    $database->check($_POST["id"], PURGE);

    $database->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "database",
        4,
        "management",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $database->redirectToList();
} elseif (isset($_POST["update"])) {
    $database->check($_POST["id"], UPDATE);

    $database->update($_POST);
    Event::log(
        $_POST["id"],
        "database",
        4,
        "management",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["management", "database"];
    Database::displayFullPageForItem($_GET['id'], $menus, [
        'databaseinstances_id' => $_GET['databaseinstances_id'],
    ]);
}
