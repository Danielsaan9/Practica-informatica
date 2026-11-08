<?php

/**
 * ChangeCost Class
 *
 * @since 0.85
 **/
class ChangeCost extends CommonITILCost
{
    // From CommonDBChild
    public static string $itemtype = Change::class;
    public static string $items_id  = 'changes_id';


    public static function canCreate(): bool
    {
        return Session::haveRight(Change::$rightname, UPDATE);
    }


    public static function canView(): bool
    {
        return Session::haveRightsOr(Change::$rightname, [Change::READALL, Change::READMY]);
    }


    public static function canUpdate(): bool
    {
        return Session::haveRight(Change::$rightname, UPDATE);
    }
}
