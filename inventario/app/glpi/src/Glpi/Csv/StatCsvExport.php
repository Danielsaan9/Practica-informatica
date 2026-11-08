<?php

namespace Glpi\Csv;

class StatCsvExport implements ExportToCsvInterface
{
    protected array $headers;

    protected array $content;

    public function __construct(array $series, array $options = [])
    {
        // Since the data for both header and content is in $series, let's parse it directly
        $this->parseSeries($series, $options);
    }

    /**
     * Parse values from $series into header and content
     *
     * @param array $series
     * @param array $options
     */
    protected function parseSeries(array $series, array $options): void
    {
        $values  = [];
        $headers = [];
        $content = [];
        $row_num = 0;

        foreach ($series as $serie) {
            $data = $serie['data'];
            if (is_array($data) && count($data)) {
                $headers[$row_num] = $serie['name'] ?? '';
                foreach ($data as $key => $val) {
                    if (!isset($values[$key])) {
                        $values[$key] = [];
                    }
                    if (isset($options['datatype']) && $options['datatype'] == 'average') {
                        $val = round($val, 2);
                    }
                    $values[$key][$row_num] = $val;
                }
            } else {
                $values[$serie['name']][] = $data;
            }
            $row_num++;
        }

        // Add an empty cell at the start
        array_unshift($headers, '');
        ksort($values);

        if (!count($headers) && $options['title']) {
            $headers[] = $options['title'];
        }

        // print values
        foreach ($values as $key => $data) {
            $content_row = [$key];
            foreach ($data as $value) {
                $content_row[] = $value;
            }
            $content[] = $content_row;
        }

        $this->headers = $headers;
        $this->content = $content;
    }

    public function getFileName(): string
    {
        return 'glpi.csv';
    }

    public function getFileHeader(): array
    {
        return $this->headers;
    }

    public function getFileContent(): array
    {
        return $this->content;
    }
}
