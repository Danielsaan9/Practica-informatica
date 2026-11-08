<?php

/// Class UserCategory
class UserCategory extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('User category', 'User categories', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-user-cog";
    }
}
