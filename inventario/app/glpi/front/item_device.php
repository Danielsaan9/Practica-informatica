<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;

global $CFG_GLPI;

$itemDevice = getItemForItemtype($_GET['itemtype']);
if (!$itemDevice) {
    throw new RuntimeException(
        'Missing or incorrect item device type called!'
    );
}

if (!$itemDevice->canView()) {
    throw new AccessDeniedHttpException();
}

if (in_array($itemDevice::class, $CFG_GLPI['devices_in_menu'])) {
    Html::header($itemDevice->getTypeName(Session::getPluralNumber()), '', "assets", strtolower($itemDevice::class));
} else {
    Html::header($itemDevice->getTypeName(Session::getPluralNumber()), '', "config", "commondevice", $itemDevice::class);
}

Search::show($itemDevice::class);

Html::footer();
