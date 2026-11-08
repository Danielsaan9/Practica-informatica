<?php

// Using sglobal instead of global as it is a PHP keyword.
// This is fixed in php 8 so to be changed back when we no longer support php 7.

namespace Glpi\Stat\Data\Sglobal;

use Glpi\Stat\StatDataAlwaysDisplay;

class StatDataAverageSatisfaction extends StatDataAlwaysDisplay
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $avgsatisfaction = $this->getDataByType($params, "inter_avgsatisfaction");

        $this->labels = array_keys($avgsatisfaction);
        $this->series = [
            [
                'name' => __('Satisfaction'),
                'data' => $avgsatisfaction,
            ],
        ];
    }

    public function getTitle(): string
    {
        return __('Satisfaction');
    }
}
