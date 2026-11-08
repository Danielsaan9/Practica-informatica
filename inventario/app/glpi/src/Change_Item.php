<?php

/**
 * Change_Item Class
 *
 * Relation between Changes and Items
 **/
class Change_Item extends CommonItilObject_Item
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Change::class;
    public static ?string $items_id_1          = 'changes_id';

    public static ?string $itemtype_2          = 'itemtype';
    public static ?string $items_id_2          = 'items_id';
    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;

    public static function getTypeName($nb = 0)
    {
        return _n('Change item', 'Change items', $nb);
    }
}
