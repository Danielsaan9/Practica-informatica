<?php

/// Class Group_RSSFeed
/// @since 0.84
class Group_RSSFeed extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = RSSFeed::class;
    public static ?string $items_id_1          = 'rssfeeds_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2          = 'groups_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get groups for a rssfeed
     *
     * @param int $rssfeeds_id ID of the rssfeed
     *
     * @return array of groups linked to a rssfeed
     **/
    public static function getGroups($rssfeeds_id)
    {
        global $DB;

        $groups = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => ['rssfeeds_id' => $rssfeeds_id],
        ]);

        foreach ($iterator as $data) {
            $groups[$data['groups_id']][] = $data;
        }
        return $groups;
    }
}
