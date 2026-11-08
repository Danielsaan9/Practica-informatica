<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Csv\CsvResponse;
use Glpi\Csv\PrinterLogCsvExport;
use Glpi\Csv\PrinterLogCsvExportComparison;
use Safe\DateTime;

Session::checkRight(Printer::$rightname, READ);

if (isset($_GET["id"])) {
    $printers = array_map(fn($id) => Printer::getById($id), $_GET["id"]);
    $interval = $_GET['interval'] ?? 'P1Y';
    $start = empty($_GET['start']) ? null : new DateTime($_GET['start']);
    $end = empty($_GET['end']) ? new DateTime() : new DateTime($_GET['end']);
    $format = $_GET['format'] ?? 'dynamic';

    if (count($printers) > 1) {
        CsvResponse::output(
            new PrinterLogCsvExportComparison(
                $printers,
                $interval,
                $start,
                $end,
                $format,
                $_GET['statistic'] ?? 'total_pages'
            ),
        );
    } else {
        CsvResponse::output(
            new PrinterLogCsvExport(
                array_shift($printers),
                $interval,
                $start,
                $end,
                $format
            ),
        );
    }
}
