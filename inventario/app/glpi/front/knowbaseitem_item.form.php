<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

$item = new KnowbaseItem_Item();

if (isset($_POST["add"])) {
    if (!isset($_POST['knowbaseitems_id']) || !isset($_POST['items_id']) || !isset($_POST['itemtype'])) {
        Session::addMessageAfterRedirect(__s('Mandatory fields are not filled!'), false, ERROR);
        Html::back();
    }

    try {
        $item->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if ($item->add($_POST)) {
        Event::log(
            $_POST["knowbaseitems_id"],
            "knowbaseitem",
            4,
            "tracking",
            sprintf(__('%s adds a link with an knowledge base'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
