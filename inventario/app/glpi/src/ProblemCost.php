<?php

/**
 * ProblemCost Class
 *
 * @since 0.85
 **/
class ProblemCost extends CommonITILCost
{
    // From CommonDBChild
    public static string $itemtype = Problem::class;
    public static string $items_id  = 'problems_id';


    public static function canCreate(): bool
    {
        return Session::haveRight(Problem::$rightname, UPDATE);
    }


    public static function canView(): bool
    {
        return Session::haveRightsOr(Problem::$rightname, [Problem::READALL, Problem::READMY]);
    }


    public static function canUpdate(): bool
    {
        return Session::haveRight(Problem::$rightname, UPDATE);
    }
}
