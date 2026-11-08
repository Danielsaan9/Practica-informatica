<?php

/// Class NetworkInterface
class NetworkInterface extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Network interface', 'Network interfaces', $nb);
    }

    public static function getIcon()
    {
        return "fas fa-ethernet";
    }
}
