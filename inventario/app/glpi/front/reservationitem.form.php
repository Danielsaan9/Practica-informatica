<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkCentralAccess();
Session::checkRightsOr(Reservation::$rightname, [CREATE, UPDATE, DELETE, PURGE]);

if (!isset($_GET["id"])) {
    $_GET["id"] = '';
}

$ri = new ReservationItem();
if (isset($_POST["add"])) {
    $ri->check(-1, CREATE, $_POST);
    if ($newID = $ri->add($_POST)) {
        Event::log(
            $newID,
            "reservationitem",
            4,
            "inventory",
            sprintf(
                __('%1$s adds the item %2$s (%3$d)'),
                $_SESSION["glpiname"],
                $_POST["itemtype"],
                $_POST["items_id"]
            )
        );
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $ri->check($_POST["id"], DELETE);
    $ri->delete($_POST);

    Event::log(
        $_POST['id'],
        "reservationitem",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["purge"])) {
    $ri->check($_POST["id"], PURGE);
    $ri->delete($_POST, true);

    Event::log(
        $_POST['id'],
        "reservationitem",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["update"])) {
    $ri->check($_POST["id"], UPDATE);
    $ri->update($_POST);
    Event::log(
        $_POST['id'],
        "reservationitem",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $ri->check($_GET["id"], READ);
    Html::header(Reservation::getTypeName(Session::getPluralNumber()), '', "tools", "reservationitem");
    $ri->showForm($_GET["id"]);
}

Html::footer();
