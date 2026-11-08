<?php

class ChangeTask extends CommonITILTask
{
    public static function getTypeName($nb = 0)
    {
        return _n('Change task', 'Change tasks', $nb);
    }


    /**
     * Populate the planning with planned ticket tasks
     *
     * @param array $options array of possible options:
     *    - who ID of the user (0 = undefined)
     *    - whogroup ID of the group of users (0 = undefined)
     *    - begin Date
     *    - end Date
     *
     * @return array of planning item
     **/
    public static function populatePlanning($options = []): array
    {
        return parent::genericPopulatePlanning(self::class, $options);
    }


    /**
     * Display a Planning Item
     *
     * @param array           $val       array of the item to display
     * @param int         $who       ID of the user (0 if all)
     * @param string          $type      position of the item in the time block (in, through, begin or end)
     * @param int|bool $complete  complete display (more details)
     *
     * @return string
     */
    public static function displayPlanningItem(array $val, $who, $type = "", $complete = 0)
    {
        return parent::genericDisplayPlanningItem(self::class, $val, $who, $type, $complete);
    }

    /**
     * Populate the planning with not planned ticket tasks
     *
     * @param array $options array of possible options:
     *    - who ID of the user (0 = undefined)
     *    - whogroup ID of the group of users (0 = undefined)
     *    - begin Date
     *    - end Date
     *
     * @return array of planning item
     **/
    public static function populateNotPlanned($options = []): array
    {
        return parent::genericPopulateNotPlanned(self::class, $options);
    }
}
