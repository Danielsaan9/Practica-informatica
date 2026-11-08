<?php

/// Class OperatingSystem
class OperatingSystem extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Operating system', 'Operating systems', $nb);
    }

    public static function getIcon()
    {
        return 'ti ti-device-desktop-cog';
    }
}
