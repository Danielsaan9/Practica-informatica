<?php

/// Class PassiveDCEquipmentModel
class PassiveDCEquipmentModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Passive device model', 'Passive device models', $nb);
    }
}
