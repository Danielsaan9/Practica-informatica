<?php

class ApplianceType extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Appliance type', 'Appliance types', $nb);
    }

    public static function getIcon()
    {
        return Appliance::getIcon();
    }
}
