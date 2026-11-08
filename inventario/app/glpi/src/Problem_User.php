<?php

/// Class Problem_User
class Problem_User extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Problem::class;
    public static ?string $items_id_1 = 'problems_id';
    public static ?string $itemtype_2 = User::class;
    public static ?string $items_id_2 = 'users_id';
}
