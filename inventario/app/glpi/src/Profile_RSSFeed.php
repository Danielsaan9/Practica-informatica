<?php

/// Class Profile_RSSFeed
/// @since 0.84
class Profile_RSSFeed extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1          = RSSFeed::class;
    public static ?string $items_id_1          = 'rssfeeds_id';
    public static ?string $itemtype_2 = Profile::class;
    public static ?string $items_id_2          = 'profiles_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get profiles for a rssfeed
     *
     * @param int $rssfeeds_id ID of the rssfeed
     *
     * @return array
     */
    public static function getProfiles($rssfeeds_id)
    {
        global $DB;

        $prof  = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => ['rssfeeds_id' => $rssfeeds_id],
        ]);

        foreach ($iterator as $data) {
            $prof[$data['profiles_id']][] = $data;
        }
        return $prof;
    }
}
