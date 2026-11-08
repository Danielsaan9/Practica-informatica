<?php

/// Class Group_Problem
class Group_Problem extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Problem::class;
    public static ?string $items_id_1 = 'problems_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2 = 'groups_id';
}
