<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

Session::checkRight(Software::$rightname, UPDATE);

if (
    isset($_POST["add"])
    && (!isset($_POST['itemtype']) || !isset($_POST['items_id']) || $_POST['items_id'] <= 0)
) {
    $message = sprintf(
        __('Mandatory fields are not filled. Please correct: %s'),
        _n('Item', 'Items', 1)
    );
    Session::addMessageAfterRedirect(htmlescape($message), false, ERROR);
    Html::back();
}

if (isset($_POST['itemtype']) && $_POST['itemtype'] == 'User') {
    $isl = new SoftwareLicense_User();
    // convert form data to match the SoftwareLicense_User case
    $_POST['users_id'] = $_POST['items_id'];
    unset($_POST['itemtype'], $_POST['items_id']);
} else {
    $isl = new Item_SoftwareLicense();
}

if (isset($_POST["add"])) {
    try {
        $isl->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if (isset($_POST['softwarelicenses_id']) && $_POST['softwarelicenses_id'] > 0) {
        if ($isl->add($_POST)) {
            Event::log(
                $_POST['softwarelicenses_id'],
                "softwarelicense",
                4,
                "inventory",
                //TRANS: %s is the user login
                sprintf(__('%s associates an item and a license'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
}

throw new BadRequestHttpException();
