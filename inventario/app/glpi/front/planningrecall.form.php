<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.84.3
 */

$pr = new PlanningRecall();

if (isset($_POST["update"])) {
    $pr->manageDatas($_POST['_planningrecall']);
    Html::back();
}

throw new BadRequestHttpException();
