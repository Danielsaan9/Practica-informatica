<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["projects_id"])) {
    $_GET["projects_id"] = "";
}

$cost = new ProjectCost();
if (isset($_POST["add"])) {
    $cost->check(-1, CREATE, $_POST);

    if ($cost->add($_POST)) {
        Event::log(
            $_POST['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s adds a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $cost->check($_POST["id"], PURGE);

    if ($cost->delete($_POST, true)) {
        Event::log(
            $cost->fields['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cost'), $_SESSION["glpiname"])
        );
    }

    Html::redirect(Toolbox::getItemTypeFormURL('Project') . '?id=' . $cost->fields['projects_id']);
} elseif (isset($_POST["update"])) {
    $cost->check($_POST["id"], UPDATE);

    if ($cost->update($_POST)) {
        Event::log(
            $cost->fields['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
