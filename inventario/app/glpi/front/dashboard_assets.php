<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Dashboard\Dashboard;
use Glpi\Dashboard\Grid;
use Glpi\Exception\Http\AccessDeniedHttpException;

global $CFG_GLPI;

Session::checkCentralAccess();
$default = Grid::getDefaultDashboardForMenu('assets');

// Redirect to "/front/computer.php" if no dashboard found
if ($default == "") {
    Html::redirect($CFG_GLPI["root_doc"] . "/front/computer.php");
}

$dashboard = new Dashboard($default);
if (!$dashboard->canViewCurrent()) {
    throw new AccessDeniedHttpException();
}

Html::header(__('Assets Dashboard'), '', "assets", "dashboard");

$grid = new Grid($default);
$grid->showDefault();

Html::footer();
