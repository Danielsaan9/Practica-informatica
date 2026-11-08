<?php

namespace Glpi\Csv;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class CsvResponse
{
    /**
     * Output a CSV file
     *
     * @param ExportToCsvInterface $export
     */
    public static function output(ExportToCsvInterface $export): void
    {
        $spreadsheet = new Spreadsheet();
        $writer = new Csv($spreadsheet);
        $writer
            ->setDelimiter($_SESSION["glpicsv_delimiter"] ?? ";")
            ->setEnclosure('"')
            ->setUseBOM(true)
            ->setLineEnding("\r\n")
            ->setSheetIndex(0);
        $spreadsheet->getActiveSheet()->fromArray($export->getFileHeader());
        $spreadsheet->getActiveSheet()->fromArray($export->getFileContent(), null, 'A2');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $export->getFileName() . '"');
        $writer->save('php://output');
    }
}
