<?php

/**
 * Change_Group Class
 *
 * @since 0.85
 *
 *  Relation between Changes and Groups
 **/
class Change_Group extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Change::class;
    public static ?string $items_id_1 = 'changes_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2 = 'groups_id';
}
