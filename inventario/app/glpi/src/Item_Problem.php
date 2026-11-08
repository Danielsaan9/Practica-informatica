<?php

/**
 * Item_Problem Class
 *
 *  Relation between Problems and Items
 **/
class Item_Problem extends CommonItilObject_Item
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Problem::class;
    public static ?string $items_id_1          = 'problems_id';

    public static ?string $itemtype_2          = 'itemtype';
    public static ?string $items_id_2          = 'items_id';
    public static int $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;

    public static function getTypeName($nb = 0)
    {
        return _n('Problem item', 'Problem items', $nb);
    }
}
