<?php

namespace Glpi\Csv;

use Printer;
use PrinterLog;
use Safe\DateTime;

class PrinterLogCsvExport implements ExportToCsvInterface
{
    protected Printer $printer;
    protected string $interval;
    protected ?DateTime $start_date;
    protected ?DateTime $end_date;
    protected string $format;

    public function __construct(
        Printer $printer,
        string $interval,
        ?DateTime $start_date,
        ?DateTime $end_date,
        string $format
    ) {
        $this->printer = $printer;
        $this->interval = $interval;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->format = $format;
    }

    public function getFileName(): string
    {
        return !empty($this->printer->fields['name'])
            ? "{$this->printer->fields['name']}.csv"
            : "printer_{$this->printer->getID()}.csv";
    }

    public function getFileHeader(): array
    {
        $headers = [
            'date' => _n('Date', 'Dates', 1),
        ];

        foreach (
            PrinterLog::getMetrics(
                $this->printer,
                [],
                $this->interval,
                $this->start_date,
                $this->end_date,
                $this->format
            )[$this->printer->getID()][0] as $key => $value
        ) {
            $label = PrinterLog::getLabelFor($key);
            if ($label && $value > 0) {
                $headers[$key] = $label;
            }
        }

        return $headers;
    }

    public function getFileContent(): array
    {
        return array_map(function ($metric) {
            $fields = [];

            foreach ($metric as $key => $value) {
                $label = PrinterLog::getLabelFor($key);
                if ($label && $value > 0) {
                    $fields[$key] = $value;
                }
            }

            return [
                'date' => $metric['date'],
            ] + $fields;
        }, PrinterLog::getMetrics(
            $this->printer,
            [],
            $this->interval,
            $this->start_date,
            $this->end_date,
            $this->format
        )[$this->printer->getID()]);
    }
}
