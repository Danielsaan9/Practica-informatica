<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Csv\CsvResponse;
use Glpi\Csv\StatCsvExport;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Stat\StatData;

// Check rights
Session::checkRight(Stat::$rightname, READ);

// Read params
$statdata_itemtype = $_GET['statdata_itemtype'] ?? null;

// Validate stats itemtype
if (!is_a($statdata_itemtype, StatData::class, true)) {
    throw new BadRequestHttpException("Invalid stats itemtype");
}

// Get data and output csv
$graph_data = new $statdata_itemtype($_GET);
CsvResponse::output(
    new StatCsvExport($graph_data->getSeries(), $graph_data->getOptions())
);
