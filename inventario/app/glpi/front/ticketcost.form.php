<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

$cost = new TicketCost();
if (isset($_POST["add"])) {
    $cost->check(-1, CREATE, $_POST);

    if ($cost->add($_POST)) {
        Event::log(
            $_POST['tickets_id'],
            "tickets",
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s adds a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $cost->check($_POST["id"], PURGE);
    if ($cost->delete($_POST, true)) {
        Event::log(
            $cost->fields['tickets_id'],
            "tickets",
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cost'), $_SESSION["glpiname"])
        );
    }
    Html::redirect(Toolbox::getItemTypeFormURL('Ticket') . '?id=' . $cost->fields['tickets_id']);
} elseif (isset($_POST["update"])) {
    $cost->check($_POST["id"], UPDATE);

    if ($cost->update($_POST)) {
        Event::log(
            $cost->fields['tickets_id'],
            "tickets",
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
