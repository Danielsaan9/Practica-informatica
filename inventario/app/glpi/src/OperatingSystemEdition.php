<?php

class OperatingSystemEdition extends CommonDropdown
{
    public bool $can_be_translated = true;

    public static function getTypeName($nb = 0)
    {
        return _n('Edition', 'Editions', $nb);
    }
}
