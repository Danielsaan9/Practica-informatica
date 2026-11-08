<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Csv\CsvResponse;
use Glpi\Csv\LogCsvExport;
use Glpi\Exception\Http\BadRequestHttpException;

// Read params
$itemtype = $_GET['itemtype']   ?? null;
$id       = $_GET['id']         ?? null;
$filter   = $_GET['filter']     ?? [];

Session::checkRight(Log::$rightname, READ);

// Validate itemtype
if (!is_a($itemtype, CommonDBTM::class, true)) {
    throw new BadRequestHttpException("Invalid itemtype");
}

// Validate id
$item = $itemtype::getById($id);
if (!$item || !$item->can($id, READ)) {
    throw new BadRequestHttpException("No item found for given id");
}

// Validate filter
if (!is_array($filter)) {
    throw new BadRequestHttpException("Invalid filter");
}

CsvResponse::output(new LogCsvExport($item, $filter));
