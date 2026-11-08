<?php

class DatabaseInstanceCategory extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Database instance category', 'Database instance categories', $nb);
    }

    public static function getFieldLabel()
    {
        return _n('Category', 'Categories', 1);
    }
}
