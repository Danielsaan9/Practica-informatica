<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

$document_item   = new Document_Item();

if (isset($_POST["add"])) {
    try {
        $document_item->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }
    if ($document_item->add($_POST)) {
        Event::log(
            $_POST["documents_id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
