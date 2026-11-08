<?php

namespace Glpi\Stat\Data\Graph;

use Glpi\Stat\StatDataAlwaysDisplay;
use Session;

class StatDataSatisfactionSurvey extends StatDataAlwaysDisplay
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
