<?php

/**
 * @since 11.0.0
 *
 * Change_Change Class
 *
 * Relation between Changes and other Changes
 **/
class Change_Change extends CommonITILObject_CommonITILObject
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Change::class;
    public static ?string $items_id_1   = 'changes_id_1';

    public static ?string $itemtype_2 = Change::class;
    public static ?string $items_id_2   = 'changes_id_2';

    public static function getTypeName($nb = 0)
    {
        return _n('Link Change/Change', 'Links Change/Change', $nb);
    }
}
