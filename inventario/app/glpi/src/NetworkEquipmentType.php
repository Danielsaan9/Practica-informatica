<?php

/// Class NetworkEquipementType
class NetworkEquipmentType extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Networking equipment type', 'Networking equipment types', $nb);
    }

    public static function getFieldLabel()
    {
        return _n('Type', 'Types', 1);
    }
}
