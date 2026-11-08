<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\System\Log\LogParser;
use Glpi\System\Log\LogViewer;

global $CFG_GLPI;

$filepath = $_REQUEST['filepath'] ?? null;

// no file to view specified -> redirect to list of system logs
if ($filepath === null) {
    Html::redirect($CFG_GLPI["root_doc"] . "/front/logs.php");
}

$logparser = new LogParser();
if ($logparser->getFullPath($filepath) === null) {
    throw new NotFoundHttpException('Not found');
}

LogParser::checkReAuthenticationOrRedirect();

// download log file
if (($_GET['action'] ?? '') === 'download_log_file') {
    LogViewer::checkReAuthenticationOrRedirect();
    Session::checkRight(LogViewer::$rightname, READ);
    return $logparser->download($filepath);
}
// empty log file
elseif (($_POST['action'] ?? '') === 'empty') {
    // LogViewer just has a READ right, so UPDATE is checked on Config
    Config::checkReAuthenticationOrRedirect();
    Session::checkRight(Config::$rightname, UPDATE);
    $logparser->empty($filepath);
    Html::back();
}
// delete log file
elseif (($_POST['action'] ?? '') === 'delete') {
    Config::checkReAuthenticationOrRedirect();
    Session::checkRight(Config::$rightname, UPDATE); // LogViewer just has a READ right, so UPDATE is checked on Config
    $logparser->delete($filepath);
    Html::redirect($CFG_GLPI["root_doc"] . "/front/logs.php");
}
// display logs
else {
    LogViewer::checkReAuthenticationOrRedirect();
    Session::checkRight(LogViewer::$rightname, READ);
    Html::header(
        LogViewer::getTypeName(Session::getPluralNumber()),
        '',
        'admin',
        'glpi\system\log\logviewer',
        'logfile'
    );

    $logviewer = new LogViewer();
    $logviewer->showLogFile($filepath);

    Html::footer();
}
