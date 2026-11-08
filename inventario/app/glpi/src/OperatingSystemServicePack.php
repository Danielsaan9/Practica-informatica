<?php

/// Class OperatingSystemServicePack
class OperatingSystemServicePack extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Service pack', 'Service packs', $nb);
    }
}
