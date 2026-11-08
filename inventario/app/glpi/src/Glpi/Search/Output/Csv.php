<?php

namespace Glpi\Search\Output;

final class Csv extends Spreadsheet
{
    public function __construct()
    {
        parent::__construct();
        $this->writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($this->spread);
        $this->writer
            ->setDelimiter($_SESSION["glpicsv_delimiter"])
            ->setEnclosure('"')
            ->setUseBOM(true)
            ->setLineEnding("\r\n")
            ->setSheetIndex(0);
    }

    public function getMime(): string
    {
        return 'text/csv';
    }

    public function getFileName(): string
    {
        return "glpi.csv";
    }
}
