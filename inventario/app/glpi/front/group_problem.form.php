<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

global $CFG_GLPI;

$link = new Group_Problem();
$item = new Problem();

if (isset($_POST['delete'])) {
    $link->check($_POST['id'], DELETE);
    $link->delete($_POST);

    Event::log(
        $link->fields['problems_id'],
        "problem",
        4,
        "maintain",
        sprintf(__('%s deletes an actor'), $_SESSION["glpiname"])
    );

    if ($item->can($link->fields["problems_id"], READ)) {
        Html::redirect(Problem::getFormURLWithID($link->fields['problems_id']));
    }
    Session::addMessageAfterRedirect(
        __s('You have been redirected because you no longer have access to this item'),
        true,
        ERROR
    );

    Html::redirect($CFG_GLPI["root_doc"] . "/front/problem.php");
}

throw new BadRequestHttpException();
