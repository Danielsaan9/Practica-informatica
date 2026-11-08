<?php

// Using sglobal instead of global as it is a PHP keyword.
// This is fixed in php 8 so to be changed back when we no longer support php 7.

namespace Glpi\Stat\Data\Sglobal;

use Glpi\Stat\StatDataAlwaysDisplay;
use Session;

class StatDataSatisfaction extends StatDataAlwaysDisplay
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $opensatisfaction   = $this->getDataByType($params, "inter_opensatisfaction");
        $answersatisfaction = $this->getDataByType($params, "inter_answersatisfaction");

        $this->labels = array_keys($opensatisfaction);
        $this->series = [
            [
                'name' => _nx('survey', 'Opened', 'Opened', Session::getPluralNumber()),
                'data' => $opensatisfaction,
            ], [
                'name' => _nx('survey', 'Answered', 'Answered', Session::getPluralNumber()),
                'data' => $answersatisfaction,
            ],
        ];
    }

    public function getTitle(): string
    {
        return __('Satisfaction survey') . " - " . __('Tickets');
    }
}
