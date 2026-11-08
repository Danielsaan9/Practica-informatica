<?php

/// Class PhonePowerSupply
class PhonePowerSupply extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Phone power supply type', 'Phone power supply types', $nb);
    }
}
