<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

Session::checkCentralAccess();

$team = new ProjectTeam();

if (isset($_POST["add"])) {
    $team->check(-1, CREATE, $_POST);
    if ($team->add($_POST)) {
        Event::log(
            $_POST["projects_id"],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s adds a team member'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
