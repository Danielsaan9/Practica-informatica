<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$config = new Config();
$config->checkGlobal(UPDATE);

Html::header(
    __('External authentication sources'),
    '',
    "config",
    "auth",
    "settings"
);
$config->showFormAuthentication();

Html::footer();
