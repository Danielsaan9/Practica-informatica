<?php

class DropdownVisibility extends CommonDBChild
{
    public static string $itemtype        = 'itemtype';
    public static string $items_id        = 'items_id';
    public bool $dohistory              = false;
    public static bool $logs_for_parent = false;
}
