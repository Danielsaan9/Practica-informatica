<?php

/// Class PeripheralType
class PeripheralType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Devices type', 'Devices types', $nb);
    }
}
