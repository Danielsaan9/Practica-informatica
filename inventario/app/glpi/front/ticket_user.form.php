<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

global $CFG_GLPI;

$link = new Ticket_User();
$item = new Ticket();

Html::popHeader(__('Email followup'));

if (isset($_POST["update"])) {
    $link->check($_POST["id"], UPDATE);

    if ($link->update($_POST)) {
        echo "<script type='text/javascript' >";
        echo "window.parent.location.reload();";
        echo "</script>";
    } else {
        Html::back();
    }
} elseif (isset($_POST['delete'])) {
    $link->check($_POST['id'], DELETE);
    $link->delete($_POST);

    Event::log(
        $link->fields['tickets_id'],
        "ticket",
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an actor'), $_SESSION["glpiname"])
    );
    if ($item->can($link->fields["tickets_id"], READ)) {
        Html::redirect(Ticket::getFormURLWithID($link->fields['tickets_id']));
    }
    Session::addMessageAfterRedirect(
        __s('You have been redirected because you no longer have access to this item'),
        true,
        ERROR
    );

    Html::redirect($CFG_GLPI["root_doc"] . "/front/ticket.php");
} else {
    throw new BadRequestHttpException();
}

Html::popFooter();
