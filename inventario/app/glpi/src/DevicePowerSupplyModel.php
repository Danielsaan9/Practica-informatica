<?php

/// Class DevicePowerSupplyModel
class DevicePowerSupplyModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device power supply model', 'Device power supply models', $nb);
    }
}
