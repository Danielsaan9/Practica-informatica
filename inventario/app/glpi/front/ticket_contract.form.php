<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

$item = new Ticket_Contract();

if (isset($_POST["add"])) {
    try {
        $item->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }
    $item->add($_POST);

    Html::back();
}

throw new BadRequestHttpException();
