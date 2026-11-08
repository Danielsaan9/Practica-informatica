<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

$group_user = new Group_User();

if (isset($_POST["add"])) {
    try {
        $group_user->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if ($group_user->add($_POST)) {
        Event::log(
            $_POST["groups_id"],
            "groups",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a user to a group'), $_SESSION["glpiname"])
        );
    }

    Html::back();
}

throw new BadRequestHttpException();
