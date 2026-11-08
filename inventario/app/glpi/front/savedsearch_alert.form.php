<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["savedsearches_id"])) {
    $_GET["savedsearches_id"] = "";
}

$alert = new SavedSearch_Alert();
if (isset($_POST["add"])) {
    $alert->check(-1, CREATE, $_POST);

    if ($alert->add($_POST)) {
        Event::log(
            $_POST['savedsearches_id'],
            "savedsearches",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds an alert'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($alert->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $alert->check($_POST["id"], PURGE);

    if ($alert->delete($_POST, true)) {
        Event::log(
            $alert->fields['savedsearches_id'],
            "savedsearches",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an alert'), $_SESSION["glpiname"])
        );
    }
    $search = new SavedSearch();
    $search->getFromDB($alert->fields['savedsearches_id']);
    Html::redirect(Toolbox::getItemTypeFormURL('SavedSearch') . '?id=' . $alert->fields['savedsearches_id']);
} elseif (isset($_POST["update"])) {
    $alert->check($_POST["id"], UPDATE);

    if ($alert->update($_POST)) {
        Event::log(
            $alert->fields['savedsearches_id'],
            "savedsearches",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates an alert'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menu = ["tools", "savedsearch"];
    SavedSearch_Alert::displayFullPageForItem($_GET["id"], $menu, [
        'savedsearches_id' => $_GET["savedsearches_id"],
    ]);
}
