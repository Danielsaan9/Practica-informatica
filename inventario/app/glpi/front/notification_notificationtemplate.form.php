<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

//Html::back();
//
if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$notiftpl = new Notification_NotificationTemplate();
if (isset($_POST["add"])) {
    $notiftpl->check(-1, CREATE, $_POST);

    if ($notiftpl->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($notiftpl->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $notiftpl->check($_POST["id"], PURGE);
    $notiftpl->delete($_POST, true);
    Html::redirect(Notification::getFormURLWithID($notiftpl->fields['notifications_id']));
} elseif (isset($_POST["update"])) {
    $notiftpl->check($_POST["id"], UPDATE);

    $notiftpl->update($_POST);
    Html::back();
} else {
    $params = [];
    if (isset($_GET['notifications_id'])) {
        $params['notifications_id'] = $_GET['notifications_id'];
    }

    $menus = ["config", "notification", "Notification_NotificationTemplate"];
    Notification_NotificationTemplate::displayFullPageForItem(
        $_GET['id'],
        $menus,
        $params
    );
}
