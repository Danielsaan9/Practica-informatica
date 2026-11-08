<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Dashboard\Dashboard;
use Glpi\Dashboard\Grid;
use Glpi\Exception\Http\AccessDeniedHttpException;

global $CFG_GLPI;

Session::checkCentralAccess();
$default = Grid::getDefaultDashboardForMenu('helpdesk');

// Redirect to "/front/ticket.php" if no dashboard found
if ($default == "") {
    Html::redirect($CFG_GLPI["root_doc"] . "/front/ticket.php");
}

$dashboard = new Dashboard($default);
if (!$dashboard->canViewCurrent()) {
    throw new AccessDeniedHttpException();
}

Html::header(__('Helpdesk Dashboard'), '', "helpdesk", "dashboard");

$grid = new Grid($default);
$grid->showDefault();

Html::footer();
