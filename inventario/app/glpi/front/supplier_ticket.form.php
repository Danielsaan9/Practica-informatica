<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

$link = new Supplier_Ticket();

Html::popHeader(__('Email followup'));

if (isset($_POST["update"])) {
    $link->check($_POST["id"], UPDATE);

    $link->update($_POST);
    echo "<script type='text/javascript' >";
    echo "window.parent.location.reload();";
    echo "</script>";
} elseif (isset($_POST['delete'])) {
    $link->check($_POST['id'], DELETE);
    $link->delete($_POST);

    Event::log(
        $link->fields['tickets_id'],
        "ticket",
        4,
        "tracking",
        sprintf(__('%s deletes an actor'), $_SESSION["glpiname"])
    );
    Html::redirect(Ticket::getFormURLWithID($link->fields['tickets_id']));
} else {
    throw new BadRequestHttpException();
}

Html::popFooter();
