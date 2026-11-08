<?php

/// Class Entity_Reminder
/// @since 0.83
class Entity_Reminder extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Reminder::class;
    public static ?string $items_id_1          = 'reminders_id';
    public static ?string $itemtype_2 = Entity::class;
    public static ?string $items_id_2          = 'entities_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get entities for a reminder
     *
     * @param Reminder $reminder Reminder instance
     *
     * @return array of entities linked to a reminder
     **/
    public static function getEntities($reminder)
    {
        global $DB;

        $ent   = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'reminders_id' => $reminder->fields['id'],
            ],
        ]);

        foreach ($iterator as $data) {
            $ent[$data['entities_id']][] = $data;
        }
        return $ent;
    }
}
