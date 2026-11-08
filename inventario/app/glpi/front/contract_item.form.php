<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84
 */

Session::checkCentralAccess();

if (isset($_POST["add"])) {
    if (!isset($_POST['contracts_id']) || empty($_POST['contracts_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Contract::getTypeName(1)
        );
        Session::addMessageAfterRedirect(htmlescape($message), false, ERROR);
        Html::back();
    }

    if (isset($_POST['itemtype']) && $_POST['itemtype'] == 'User') {
        $contract_item = new Contract_User();
        // convert form data to match the Contract_User case
        $_POST['users_id'] = $_POST['items_id'];
        unset($_POST['itemtype'], $_POST['items_id']);
    } else {
        $contract_item   = new Contract_Item();
    }

    $contract_item->check(-1, CREATE, $_POST);
    if ($contract_item->add($_POST)) {
        Event::log(
            $_POST["contracts_id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
