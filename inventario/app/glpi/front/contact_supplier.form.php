<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();
$contactsupplier = new Contact_Supplier();
if (isset($_POST["add"])) {
    try {
        $contactsupplier->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if (
        isset($_POST["contacts_id"]) && ($_POST["contacts_id"] > 0)
        && isset($_POST["suppliers_id"]) && ($_POST["suppliers_id"] > 0)
    ) {
        if ($contactsupplier->add($_POST)) {
            Event::log(
                $_POST["contacts_id"],
                "contacts",
                4,
                "financial",
                //TRANS: %s is the user login
                sprintf(__('%s adds a link with a supplier'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
}

throw new BadRequestHttpException();
