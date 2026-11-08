<?php

/// Class OperatingSystemServicePack
class OperatingSystemArchitecture extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Operating system architecture', 'Operating system architectures', $nb);
    }
}
