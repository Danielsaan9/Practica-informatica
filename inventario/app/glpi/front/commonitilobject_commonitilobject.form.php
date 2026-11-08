<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 11.0.0
 */

Session::checkCentralAccess();

if (isset($_POST['purge'], $_POST['id'])) {
    [$link_class_1, $link_class_2, $link_id] = explode('_', $_POST['id'], 3);
    $link_class = $link_class_1 . '_' . $link_class_2;
    $itil_itil = getItemForItemtype($link_class);
    $_POST['id'] = (int) $link_id;
    $itil_itil->check($_POST['id'], PURGE);

    $itil_itil->delete($_POST, true);

    Event::log(
        $_POST['items_id'],
        strtolower($_POST['itemtype']),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges link between ITIL Objects'), $_SESSION["glpiname"])
    );
    Html::redirect($_POST['itemtype']::getFormURLWithID($_POST['items_id']));
}

throw new BadRequestHttpException();
