<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

NotificationTarget::updateTargets($_POST);
Html::back();
