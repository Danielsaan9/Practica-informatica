<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();

$icl = new Item_Cluster();
$cluster = new Cluster();

if (isset($_POST['add'])) {
    $icl->check(-1, CREATE, $_POST);
    $icl->add($_POST);
    $url = $cluster->getFormURLWithID($_POST['clusters_id']);
    Html::back();
}

throw new BadRequestHttpException();
