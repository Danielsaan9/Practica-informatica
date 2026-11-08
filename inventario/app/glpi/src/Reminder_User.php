<?php

/// Class Reminder_User
/// @since 0.83
class Reminder_User extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1          = Reminder::class;
    public static ?string $items_id_1          = 'reminders_id';
    public static ?string $itemtype_2          = User::class;
    public static ?string $items_id_2          = 'users_id';

    public static int $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;

    /**
     * Get users for a reminder
     *
     * @param int $reminders_id ID of the reminder
     *
     * @return array of users linked to a reminder
     */
    public static function getUsers($reminders_id)
    {
        global $DB;

        $users = [];

        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'reminders_id' => $reminders_id,
            ],
        ]);

        foreach ($iterator as $data) {
            $users[$data['users_id']][] = $data;
        }
        return $users;
    }
}
