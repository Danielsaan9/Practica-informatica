<?php

/// Class OperatingSystemVersion
class OperatingSystemVersion extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Version of the operating system', 'Versions of the operating systems', $nb);
    }
}
