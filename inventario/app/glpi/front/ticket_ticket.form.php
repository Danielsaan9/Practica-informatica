<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

$ticket_ticket = new Ticket_Ticket();

Session::checkCentralAccess();

Toolbox::deprecated();

if (isset($_POST['purge'])) {
    $ticket_ticket->check($_POST['id'], PURGE);

    $ticket_ticket->delete($_POST, true);

    Event::log(
        $_POST['tickets_id'],
        "ticket",
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges link between tickets'), $_SESSION["glpiname"])
    );
    Html::redirect(Ticket::getFormURLWithID($_POST['tickets_id']));
}

throw new BadRequestHttpException();
