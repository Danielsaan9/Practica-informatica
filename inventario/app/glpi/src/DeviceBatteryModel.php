<?php

/// Class DeviceBatteryModel
class DeviceBatteryModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];

    public static function getTypeName($nb = 0)
    {
        return _n('Device battery model', 'Device batteries models', $nb);
    }
}
