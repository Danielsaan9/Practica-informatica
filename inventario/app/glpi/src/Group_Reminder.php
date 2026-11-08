<?php

/// Class Group_Reminder
/// @since 0.83
class Group_Reminder extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Reminder::class;
    public static ?string $items_id_1          = 'reminders_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2          = 'groups_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get groups for a reminder
     *
     * @param int $reminders_id ID of the reminder
     *
     * @return array of groups linked to a reminder
     **/
    public static function getGroups($reminders_id)
    {
        global $DB;

        $groups = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'reminders_id' => $reminders_id,
            ],
        ]);

        foreach ($iterator as $data) {
            $groups[$data['groups_id']][] = $data;
        }
        return $groups;
    }
}
