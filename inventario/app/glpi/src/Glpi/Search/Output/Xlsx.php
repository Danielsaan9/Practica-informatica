<?php

namespace Glpi\Search\Output;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Safe\DateTime;

final class Xlsx extends Spreadsheet
{
    public function __construct()
    {
        parent::__construct();
        $this->writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spread);
    }

    public function getMime(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public function getFileName(): string
    {
        return "glpi.xlsx";
    }

    /**
     * Format and set the value of a cell
     *
     * @param Worksheet $worksheet
     * @param int $x_coordinate Column index
     * @param int $y_coordinate Row index
     * @param mixed $value
     * @param array<string, mixed> $options
     * @return void
     */
    protected function formatValue(
        Worksheet $worksheet,
        int $x_coordinate,
        int $y_coordinate,
        mixed $value,
        array $options = []
    ): void {
        // If the value is an array and search options are provided, it's a search result item
        if (is_array($value) && isset($options['searchopt'])) {
            $is_date = in_array($options['searchopt']['datatype'] ?? '', ['date', 'datetime']);
            $raw_val = $value[0]['name'] ?? null;
            $is_single_value = ($value['count'] ?? 0) === 1;

            // Attempt to format as a native Excel date if applicable
            if ($is_date && $is_single_value && !empty($raw_val) && $raw_val !== 'NULL') {
                try {
                    $dateTime = new DateTime($raw_val);
                    $glpiFormat = (int) ($_SESSION["glpidate_format"] ?? 0);

                    $formatMap = [
                        0 => ['excel' => 'yyyy-mm-dd'],
                        1 => ['excel' => 'dd-mm-yyyy'],
                        2 => ['excel' => 'mm-dd-yyyy'],
                        3 => ['excel' => 'dd.mm.yyyy'],
                    ];
                    $selected = $formatMap[$glpiFormat] ?? $formatMap[0];

                    // Convert to Excel numeric date format
                    $excelDateValue = Date::PHPToExcel($dateTime);
                    $worksheet->setCellValue([$x_coordinate, $y_coordinate], $excelDateValue);

                    $excelFormat = $selected['excel'] . (($options['searchopt']['datatype'] === 'datetime') ? ' hh:mm' : '');

                    // Apply the native Excel cell style for dates
                    $worksheet->getStyle([$x_coordinate, $y_coordinate])
                        ->getNumberFormat()
                        ->setFormatCode($excelFormat);
                    return; // Success: native date applied, we exit.
                } catch (\Throwable $e) {
                    // Parsing failed: we intentionally swallow the error and fall through
                    // to the parent's default behavior below.
                }
            }
        }

        // Fallback: delegate to the parent class for standard text/date string formatting
        parent::formatValue($worksheet, $x_coordinate, $y_coordinate, $value, $options);
    }
}
