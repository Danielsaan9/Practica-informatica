<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

$inst = new Item_SoftwareVersion();

// From asset - Software tab (add form)
if (isset($_POST['add'])) {
    if (
        isset($_POST['itemtype']) && isset($_POST['items_id']) && $_POST['items_id']
        && isset($_POST['softwareversions_id']) && $_POST['softwareversions_id']
    ) {
        $input = [
            'itemtype'            => $_POST['itemtype'],
            'items_id'            => $_POST['items_id'],
            'softwareversions_id' => $_POST['softwareversions_id'],
        ];
        $inst->check(-1, CREATE, $input);
        if ($inst->add($input)) {
            Event::log(
                $_POST["items_id"],
                $_POST['itemtype'],
                5,
                "inventory",
                //TRANS: %s is the user login
                sprintf(__('%s installs software'), $_SESSION["glpiname"])
            );
        }
    } else {
        $message = null;
        if (!isset($_POST['softwares_id']) || !$_POST['softwares_id']) {
            $message = __s('Please select a software!');
        } elseif (!isset($_POST['softwareversions_id']) || !$_POST['softwareversions_id']) {
            $message = __s('Please select a version!');
        }

        Session::addMessageAfterRedirect($message, true, ERROR);
    }
    Html::back();
}

throw new BadRequestHttpException();
