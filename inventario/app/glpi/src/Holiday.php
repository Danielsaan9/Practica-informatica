<?php

/**
 * Holiday Class
 **/
class Holiday extends CommonDropdown
{
    public static string $rightname = 'calendar';

    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Close time', 'Close times', $nb);
    }


    public function getAdditionalFields()
    {

        return [['name'  => 'begin_date',
            'label' => __('Start'),
            'type'  => 'date',
        ],
            ['name'  => 'end_date',
                'label' => __('End'),
                'type'  => 'date',
            ],
            ['name'  => 'is_perpetual',
                'label' => __('Recurrent'),
                'type'  => 'bool',
            ],
        ];
    }


    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '11',
            'table'              => $this->getTable(),
            'field'              => 'begin_date',
            'name'               => __('Start'),
            'datatype'           => 'date',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => $this->getTable(),
            'field'              => 'end_date',
            'name'               => __('End'),
            'datatype'           => 'date',
        ];

        $tab[] = [
            'id'                 => '13',
            'table'              => $this->getTable(),
            'field'              => 'is_perpetual',
            'name'               => __('Recurrent'),
            'datatype'           => 'bool',
        ];

        return $tab;
    }


    public function prepareInputForAdd($input)
    {

        $input = parent::prepareInputForAdd($input);

        if (
            empty($input['end_date'])
            || ($input['end_date'] == 'NULL')
            || ($input['end_date'] < $input['begin_date'])
        ) {
            $input['end_date'] = $input['begin_date'];
        }
        return $input;
    }


    public function prepareInputForUpdate($input)
    {

        $input = parent::prepareInputForUpdate($input);

        if (
            isset($input['begin_date']) && (empty($input['end_date'])
            || ($input['end_date'] == 'NULL')
            || ($input['end_date'] < $input['begin_date']))
        ) {
            $input['end_date'] = $input['begin_date'];
        }

        return $input;
    }

    public function post_updateItem($history = true)
    {

        $this->invalidateCalendarHolidayCache();

        parent::post_updateItem($history);
    }

    public function post_deleteFromDB()
    {

        $this->invalidateCalendarHolidayCache();

        parent::post_deleteFromDB();
    }

    public function cleanDBonPurge()
    {

        $this->deleteChildrenAndRelationsFromDb(
            [
                Calendar_Holiday::class,
            ]
        );
    }

    /**
     * Invalidate holidays cache on linked calendars.
     *
     * @return void
     */
    private function invalidateCalendarHolidayCache(): void
    {
        $calendar_holiday = new Calendar_Holiday();
        $calendar_holiday->invalidateHolidayCache($this->fields['id']);
    }

    public static function getIcon()
    {
        return "ti ti-calendar-off";
    }
}
