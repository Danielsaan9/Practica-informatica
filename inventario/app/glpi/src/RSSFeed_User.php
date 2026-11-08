<?php

/// Class RSSFeed_User
/// @since 0.84
class RSSFeed_User extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1          = RSSFeed::class;
    public static ?string $items_id_1          = 'rssfeeds_id';
    public static ?string $itemtype_2          = User::class;
    public static ?string $items_id_2          = 'users_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get users for a rssfeed
     *
     * @param int $rssfeeds_id ID of the rssfeed
     *
     * @return array of users linked to a rssfeed
     */
    public static function getUsers($rssfeeds_id)
    {
        global $DB;

        $users = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => ['rssfeeds_id' => $rssfeeds_id],
        ]);

        foreach ($iterator as $data) {
            $users[$data['users_id']][] = $data;
        }
        return $users;
    }
}
