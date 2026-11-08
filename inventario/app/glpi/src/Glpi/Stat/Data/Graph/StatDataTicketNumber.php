<?php

namespace Glpi\Stat\Data\Graph;

use Glpi\Stat\StatDataAlwaysDisplay;
use Session;
use Ticket;

class StatDataTicketNumber extends StatDataAlwaysDisplay
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $avgsolved     = $this->getDataByType($params, "inter_avgsolvedtime");
        $avgclosed     = $this->getDataByType($params, "inter_avgclosedtime");
        $avgactiontime = $this->getDataByType($params, "inter_avgactiontime");

        foreach ($avgsolved as $key => &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);
        foreach ($avgclosed as $key => &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);
        foreach ($avgactiontime as $key => &$val) {
            $val = round($val / HOUR_TIMESTAMP, 2);
        }
        unset($val);

        $this->labels = array_keys($avgsolved);
        $this->series = [
            [
                'name' => __('Closure'),
                'data' => $avgsolved,
            ], [
                'name' => __('Resolution'),
                'data' => $avgclosed,
            ], [
                'name' => __('Real duration'),
                'data' => $avgactiontime,
            ],
        ];

        if ($params['itemtype'] == Ticket::class) {
            $avgtaketime = $this->getDataByType($params, "inter_avgtakeaccount");
            foreach ($avgtaketime as $key => &$val) {
                $val = round($val / HOUR_TIMESTAMP, 2);
            }
            unset($val);

            $this->series[] = [
                'name' => __('Take into account'),
                'data' => $avgtaketime,
            ];
        }
    }

    public function getTitle(): string
    {
        return __('Average time') . " - " . _n('Hour', 'Hours', Session::getPluralNumber());
    }
}
