<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRight(Report::$rightname, READ);
Session::checkRight(Infocom::$rightname, READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), '', "tools", "report");

Report::title();
Report::showInfocomReport($_GET['date1'] ?? null, $_GET['date2'] ?? null);
Html::footer();
