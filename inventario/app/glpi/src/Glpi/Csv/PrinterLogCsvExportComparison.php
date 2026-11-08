<?php

namespace Glpi\Csv;

use PrinterLog;
use Safe\DateTime;

class PrinterLogCsvExportComparison implements ExportToCsvInterface
{
    protected array $printers;
    protected string $interval;
    protected ?DateTime $start_date;
    protected ?DateTime $end_date;
    protected string $format;
    protected string $statistic;

    public function __construct(
        array $printers,
        string $interval,
        ?DateTime $start_date,
        ?DateTime $end_date,
        string $format,
        string $statistic,
    ) {
        $this->printers = $printers;
        $this->interval = $interval;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->format = $format;
        $this->statistic = $statistic;
    }

    public function getFileName(): string
    {
        $printer = array_shift($this->printers);
        return !empty($printer->fields['name'])
            ? "{$printer->fields['name']}_" . __('Comparison') . ".csv"
            : "printer_{$printer->getID()}_" . __('Comparison') . ".csv";
    }

    public function getFileHeader(): array
    {
        return [
            'date' => _n('Date', 'Dates', 1),
        ] + array_combine(
            array_map(
                fn($printer) => $printer->getID(),
                $this->printers
            ),
            array_map(
                fn($printer) => $printer->fields['name'] ?? $printer->getID(),
                $this->printers
            )
        );
    }

    public function getFileContent(): array
    {
        $printersMetrics = PrinterLog::getMetrics(
            $this->printers,
            [],
            $this->interval,
            $this->start_date,
            $this->end_date,
            $this->format
        );
        $content = [];

        foreach ($printersMetrics as $printerId => $metrics) {
            foreach ($metrics as $metric) {
                if (!isset($content[$metric['date']])) {
                    $content[$metric['date']] = [
                        'date' => $metric['date'],
                    ];

                    foreach ($this->printers as $printer) {
                        $content[$metric['date']][$printer->getID()] = null;
                    }
                }

                $content[$metric['date']][$printerId] = $metric[$this->statistic];
            }
        }

        usort($content, fn($a, $b) => $a['date'] <=> $b['date']);

        // Fill null values with previous non-null value
        foreach ($content as $key => $value) {
            foreach ($value as $printerId => $metric) {
                if ($metric === null) {
                    $content[$key][$printerId] = $content[$key - 1][$printerId] ?? null;
                }
            }
        }

        return $content;
    }
}
