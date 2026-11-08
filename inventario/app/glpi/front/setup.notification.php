<?php

require_once(__DIR__ . '/_check_webserver_config.php');

global $CFG_GLPI;

Session::checkSeveralRightsOr(['notification' => READ,
    'config'       => UPDATE,
]);

Html::header(_n('Notification', 'Notifications', Session::getPluralNumber()), '', "config", "notification");

if (
    !Session::haveRight(Config::$rightname, READ)
    && Session::haveRight(Notification::$rightname, READ)
) {
    Html::redirect($CFG_GLPI["root_doc"] . '/front/notification.php');
}

$settingconfig = new NotificationSettingConfig();

// Init $CFG_GLPI['notifications_modes']
Notification_NotificationTemplate::getModes();

if (count($_POST)) {
    $settingconfig->update($_POST);
    Html::back();
}

$settingconfig->showConfigForm();

Html::footer();
