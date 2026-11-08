<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

global $CFG_GLPI;

$link = new Change_User();
$item = new Change();

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
        $link->fields['changes_id'],
        "change",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an actor'), $_SESSION["glpiname"])
    );

    if ($item->can($link->fields["changes_id"], READ)) {
        Html::redirect(Change::getFormURLWithID($link->fields['changes_id']));
    }
    Session::addMessageAfterRedirect(
        __s('You have been redirected because you no longer have access to this item'),
        true,
        ERROR
    );

    Html::redirect($CFG_GLPI["root_doc"] . "/front/change.php");
} else {
    throw new BadRequestHttpException();
}

Html::popFooter();
