<?php

use Glpi\System\Log\LogViewer;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(LogViewer::$rightname, READ);

if (($_POST['action'] ?? "") == "refresh_log_file") {
    $logviewer = new LogViewer();
    $logviewer->showLogFile($_POST['filepath'], true);
}
