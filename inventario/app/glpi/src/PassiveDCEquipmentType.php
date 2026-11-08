<?php

/// Class PassiveDCEquipmentType
class PassiveDCEquipmentType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Passive device type', 'Passive device types', $nb);
    }
}
