<?php

/**
 * ChangeValidation class
 */
class ChangeValidation extends CommonITILValidation
{
    // From CommonDBChild
    public static string $itemtype = Change::class;
    public static string $items_id           = 'changes_id';

    public static string $rightname                 = 'changevalidation';


    public static function getTypeName($nb = 0)
    {
        return _n('Change approval', 'Change approvals', $nb);
    }
}
