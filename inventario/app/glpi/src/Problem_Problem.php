<?php

/**
 * @since 11.0.0
 *
 * Problem_Problem Class
 *
 * Relation between Problems and other Problems
 **/
class Problem_Problem extends CommonITILObject_CommonITILObject
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Problem::class;
    public static ?string $items_id_1   = 'problems_id_1';

    public static ?string $itemtype_2 = Problem::class;
    public static ?string $items_id_2   = 'problems_id_2';

    public static function getTypeName($nb = 0)
    {
        return _n('Link Problem/Problem', 'Links Problem/Problem', $nb);
    }
}
