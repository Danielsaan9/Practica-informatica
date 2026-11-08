<?php

/// Class Network
class Network extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Network', 'Networks', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-network";
    }
}
