<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\System\Log\LogViewer;

LogViewer::checkReAuthenticationOrRedirect();
Session::checkRight(LogViewer::$rightname, READ);

Html::header(
    LogViewer::getTypeName(Session::getPluralNumber()),
    '',
    "admin",
    'glpi\system\log\logviewer'
);

$logviewer = new LogViewer();
$logviewer->displayList(
    $_GET['order'] ?? "filename",
    $_GET['sort']  ?? "asc"
);

Html::footer();
