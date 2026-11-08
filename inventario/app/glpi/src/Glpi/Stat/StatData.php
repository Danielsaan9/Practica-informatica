<?php

namespace Glpi\Stat;

use Stat;

/**
 * Base class for stats data meant to be displayed in a pie or line graph
 */
abstract class StatData
{
    protected array $labels;
    protected array $series;
    protected int $total;
    protected string $csv_link;
    protected array $options;
    protected array $params;

    public function __construct(array $params = [])
    {
        global $CFG_GLPI;

        // Set up link to the download as csv page with the same parameters
        if (count($params)) {
            $base_link = $CFG_GLPI['root_doc'] . '/front/graph.send.php?';
            $params['statdata_itemtype'] = static::class;
            $this->csv_link = $base_link . http_build_query($params);
        }

        $this->params = $params;
        $this->labels = [];
        $this->series = [];
        $this->options = [];
        $this->total  = 0;
    }

    /**
     * @param array $params
     * @param string $type
     *
     * @return ?array
     */
    public function getDataByType(array $params, string $type)
    {
        return Stat::constructEntryValues(
            $params['itemtype'],
            $type,
            $params['date1'],
            $params['date2'],
            $params['type'] ?? "",
            $params['val1'] ?? "",
            $params['val2'] ?? ""
        );
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getSeries(): array
    {
        return $this->series;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isEmpty(): bool
    {
        return $this->total === 0;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCsvLink(): ?string
    {
        return $this->csv_link;
    }

    public function getTitle(): string
    {
        return "";
    }
}
