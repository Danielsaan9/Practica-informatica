<?php

/// Class PeripheralModel
class PeripheralModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Peripheral model', 'Peripheral models', $nb);
    }
}
