<?php

/**
 * Change_User Class
 *
 * Relation between Changes and Users
 **/
class Change_User extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Change::class;
    public static ?string $items_id_1 = 'changes_id';
    public static ?string $itemtype_2 = User::class;
    public static ?string $items_id_2 = 'users_id';
}
