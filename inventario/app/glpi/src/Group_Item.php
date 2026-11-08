<?php

class Group_Item extends CommonDBRelation
{
    public const GROUP_TYPE_NORMAL = 1;
    public const GROUP_TYPE_TECH   = 2;

    // From CommonDBRelation
    public static ?string $itemtype_1          = Group::class;
    public static ?string $items_id_1          = 'groups_id';

    public static ?string $itemtype_2          = 'itemtype';
    public static ?string $items_id_2          = 'items_id';

    public static function getTypeName($nb = 0)
    {
        return _n('Group item', 'Group items', $nb);
    }
}
