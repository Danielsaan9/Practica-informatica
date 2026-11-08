<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$notificationtemplate = new NotificationTemplate();
if (isset($_POST["add"])) {
    $notificationtemplate->check(-1, CREATE, $_POST);

    $newID = $notificationtemplate->add($_POST);
    Event::log(
        $newID,
        "notificationtemplates",
        4,
        "notification",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
    );

    $url      = Toolbox::getItemTypeFormURL('NotificationTemplateTranslation', true);
    $url     .= "?notificationtemplates_id=$newID";
    Html::redirect($url);
} elseif (isset($_POST["purge"])) {
    $notificationtemplate->check($_POST["id"], PURGE);
    $notificationtemplate->delete($_POST, true);

    Event::log(
        $_POST["id"],
        "notificationtemplates",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $notificationtemplate->redirectToList();
} elseif (isset($_POST["update"])) {
    $notificationtemplate->check($_POST["id"], UPDATE);

    $notificationtemplate->update($_POST);
    Event::log(
        $_POST["id"],
        "notificationtemplates",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "notification", "NotificationTemplate"];
    NotificationTemplate::displayFullPageForItem($_GET["id"], $menus);
}
