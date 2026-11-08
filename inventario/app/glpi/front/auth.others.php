<?php

require_once(__DIR__ . '/_check_webserver_config.php');

global $CFG_GLPI;

$config = new Config();
$config->checkGlobal(UPDATE);

//Update CAS configuration
if (isset($_POST["update"])) {
    $_POST['id'] = Config::getConfigIDForContext('core');
    $config->update($_POST);
    Html::redirect($CFG_GLPI["root_doc"] . "/front/auth.others.php");
}

Html::header(__('External authentication sources'), '', "config", "auth", "others");

Auth::showOtherAuthList();

Html::footer();
