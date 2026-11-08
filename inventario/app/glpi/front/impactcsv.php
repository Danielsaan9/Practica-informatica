<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Csv\CsvResponse;
use Glpi\Csv\ImpactCsvExport;
use Glpi\Exception\Http\BadRequestHttpException;

$itemtype = $_GET['itemtype'] ?? '';
$items_id = $_GET['items_id'] ?? '';

// Check for mandatory params
if (empty($itemtype) || empty($items_id)) {
    throw new BadRequestHttpException();
}

// Check right
Session::checkRight($itemtype::$rightname, READ);

// Load item
$item = getItemForItemtype($itemtype);
$item->getFromDB($items_id);

CsvResponse::output(new ImpactCsvExport($item));
