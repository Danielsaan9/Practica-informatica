<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();

$right   = new Profile_User();

if (isset($_POST["add"])) {
    $right->check(-1, CREATE, $_POST);
    if ($right->add($_POST)) {
        Event::log(
            $_POST["users_id"],
            "users",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a user to an entity'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
