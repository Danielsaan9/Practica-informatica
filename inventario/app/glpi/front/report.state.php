<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.84
 */

Session::checkRight(Report::$rightname, READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), '', "tools", "report");

if (!isset($_GET["id"])) {
    $_GET["id"] = 0;
}

Report::title();
State::showSummary();
Html::footer();
