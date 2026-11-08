<?php

namespace Glpi\Csv;

use CommonDBTM;
use Html;
use Log;
use Toolbox;
use User;

class LogCsvExport implements ExportToCsvInterface
{
    protected CommonDBTM $item;

    protected array $filter;

    public function __construct(CommonDBTM $item, array $filter)
    {
        $this->item   = $item;
        $this->filter = $filter;
    }

    public function getFileName(): ?string
    {
        $name = $this->item->getFriendlyName();
        $date = date('Y_m_d', time());

        // Replace name by itemtype + id if empty
        if ($name === '') {
            $name = "{$this->item->getTypeName(1)}_{$this->item->getId()}";
        }

        return Toolbox::filename("{$name}_$date") . ".csv";
    }

    public function getFileHeader(): array
    {
        return [
            __('ID'),
            _n('Date', 'Dates', 1),
            User::getTypeName(1),
            _n('Field', 'Fields', 1),
            _x('name', 'Update'),
        ];
    }

    public function getFileContent(): array
    {
        // Get logs from DB
        $filter = Log::convertFiltersValuesToSqlCriteria($this->filter);
        $logs = Log::getHistoryData($this->item, 0, 0, $filter);

        // Remove uneeded rows
        $logs = array_map(function ($log) {
            unset($log['display_history']);
            unset($log['datatype']);
            $log['date_mod'] = Html::convDateTime($log["date_mod"]);
            return $log;
        }, $logs);

        return $logs;
    }
}
