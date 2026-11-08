<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRight(Report::$rightname, READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), '', "tools", "report");

if (!isset($_GET["id"])) {
    $_GET["id"] = 0;
}

Report::title();
Report::showReservationReportCriteria();
if ($_GET["id"] > 0) {
    Report::showReservationReport($_GET['id']);
}
Html::footer();
