<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

Session::checkCentralAccess();

$certif_item = new Certificate_Item();

if (isset($_POST["add"])) {
    try {
        $certif_item->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if ($certif_item->add($_POST)) {
        Event::log(
            $_POST["certificates_id"],
            "certificates",
            4,
            "certificate",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    foreach ($_POST["item"] as $key => $val) {
        $input = ['id' => $key];
        if ($val == 1) {
            $certif_item->check($key, UPDATE);
            $certif_item->delete($input);
        }
    }
    Html::back();
}

throw new BadRequestHttpException();
