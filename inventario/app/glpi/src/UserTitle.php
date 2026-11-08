<?php

/// Class UserTitle
class UserTitle extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('User title', 'Users titles', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-user-star";
    }
}
