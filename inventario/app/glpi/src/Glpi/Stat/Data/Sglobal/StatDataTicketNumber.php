<?php

// Using sglobal instead of global as it is a PHP keyword.
// This is fixed in php 8 so to be changed back when we no longer support php 7.

namespace Glpi\Stat\Data\Sglobal;

use Glpi\Stat\StatDataAlwaysDisplay;
use Session;

class StatDataTicketNumber extends StatDataAlwaysDisplay
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $total  = $this->getDataByType($params, "inter_total");
        $solved = $this->getDataByType($params, "inter_solved");
        $closed = $this->getDataByType($params, "inter_closed");
        $late   = $this->getDataByType($params, "inter_solved_late");

        $this->labels = array_keys($total);
        $this->series = [
            [
                'name' => _nx('ticket', 'Opened', 'Opened', Session::getPluralNumber()),
                'data' => $total,
            ], [
                'name' => _nx('ticket', 'Solved', 'Solved', Session::getPluralNumber()),
                'data' => $solved,
            ], [
                'name' => __('Late'),
                'data' => $late,
            ], [
                'name' => __('Closed'),
                'data' => $closed,
            ],
        ];
    }

    public function getTitle(): string
    {
        $item = getItemForItemtype($this->params['itemtype']);
        return _x('Quantity', 'Number') . " - " . $item->getTypeName(Session::getPluralNumber());
    }
}
