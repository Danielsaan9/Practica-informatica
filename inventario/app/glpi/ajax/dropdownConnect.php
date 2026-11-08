<?php

use Glpi\Asset\Asset_PeripheralAsset;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!isset($_POST['fromtype']) || !($fromitem = getItemForItemtype($_POST['fromtype']))) {
    return;
}

$fromitem->checkGlobal(UPDATE);

$used = $_POST["used"] ?? [];
Asset_PeripheralAsset::dropdownConnect(
    $_POST["itemtype"],
    $_POST['fromtype'],
    $_POST['myname'],
    Session::getMatchingActiveEntities($_POST['entity_restrict']),
    $_POST["onlyglobal"],
    $used
);
