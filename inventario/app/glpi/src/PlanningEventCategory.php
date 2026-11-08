<?php

class PlanningEventCategory extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Event category', 'Event categories', $nb);
    }

    public function getAdditionalFields()
    {
        return [
            [
                'name'  => 'color',
                'label' => __('Color'),
                'type'  => 'color',
            ],
        ];
    }

    public static function getIcon()
    {
        return "ti ti-tags";
    }
}
