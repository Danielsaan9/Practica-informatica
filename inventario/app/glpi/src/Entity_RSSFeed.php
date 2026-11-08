<?php

/// Class Entity_RSSFeed
/// @since 0.84
class Entity_RSSFeed extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = RSSFeed::class;
    public static ?string $items_id_1          = 'rssfeeds_id';
    public static ?string $itemtype_2 = Entity::class;
    public static ?string $items_id_2          = 'entities_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;

    public static bool $checkAlwaysBothItems   = true;


    /**
     * Get entities for a rssfeed
     *
     * @param int $rssfeeds_id ID of the rssfeed
     *
     * @return array of entities linked to a rssfeed
     **/
    public static function getEntities($rssfeeds_id)
    {
        global $DB;

        $ent   = [];
        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => ['rssfeeds_id' => $rssfeeds_id],
        ]);

        foreach ($iterator as $data) {
            $ent[$data['entities_id']][] = $data;
        }
        return $ent;
    }
}
