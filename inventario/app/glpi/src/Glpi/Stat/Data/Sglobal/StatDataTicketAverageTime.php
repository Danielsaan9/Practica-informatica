<?php

// Using sglobal instead of global as it is a PHP keyword.
// This is fixed in php 8 so to be changed back when we no longer support php 7.

namespace Glpi\Stat\Data\Sglobal;

use Glpi\Stat\StatDataAlwaysDisplay;
use Session;

class StatDataTicketAverageTime extends StatDataAlwaysDisplay
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $avgsolved     = $this->getDataByType($params, "inter_avgsolvedtime");
        $avgclosed     = $this->getDataByType($params, "inter_avgclosedtime");
        $avgactiontime = $this->getDataByType($params, "inter_avgactiontime");

        // Convert to hours
        foreach ($avgsolved as &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);
        foreach ($avgclosed as &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);
        foreach ($avgactiontime as &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);

        $this->labels = array_keys($avgsolved);
        $this->series = [
            [
                'name' => __('Closure'),
                'data' => $avgclosed,
            ], [
                'name' => __('Resolution'),
                'data' => $avgsolved,
            ], [
                'name' => __('Real duration'),
                'data' => $avgactiontime,
            ],
        ];
    }

    public function getTitle(): string
    {
        return __('Average time') . " - " . _n('Hour', 'Hours', Session::getPluralNumber());
    }
}
