<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

if (isset($_GET["itemtype"])) {
    $itemtype = $_GET['itemtype'];
    $link     = $itemtype::getFormURL();

    // Get right sector
    $sector = Html::getMenuSectorForItemtype($itemtype) ?? 'assets';

    Html::header(__('Manage templates...'), '', $sector, $itemtype);

    CommonDBTM::listTemplates($itemtype, $link, $_GET["add"]);

    Html::footer();
}
