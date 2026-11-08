<?php

/**
 * Class Problem_Supplier
 *
 * @since 0.84
 *
 **/
class Problem_Supplier extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Problem::class;
    public static ?string $items_id_1 = 'problems_id';
    public static ?string $itemtype_2 = Supplier::class;
    public static ?string $items_id_2 = 'suppliers_id';
}
