<?php

/**
 * Change_Supplier Class
 *
 * Relation between Changes and Suppliers
 *
 * @since 0.84
 **/
class Change_Supplier extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Change::class;
    public static ?string $items_id_1 = 'changes_id';
    public static ?string $itemtype_2 = Supplier::class;
    public static ?string $items_id_2 = 'suppliers_id';
}
