<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(Config::$rightname, UPDATE);
$notificationajax = new NotificationAjaxSetting();

if (!empty($_POST["test_ajax_send"])) {
    NotificationAjax::testNotification();
    Html::back();
} elseif (!empty($_POST["update"])) {
    $config = new Config();
    $config->update($_POST);
    Event::log(0, "system", 3, "setup", sprintf(
        __('%1$s edited the browsers notifications configuration'),
        $_SESSION["glpiname"] ?? __("Unknown"),
    ));
    Html::back();
}

$menus = ["config", "notification", NotificationAjaxSetting::class];
$config_id = Config::getConfigIDForContext('core');
NotificationAjaxSetting::displayFullPageForItem($config_id, $menus);
