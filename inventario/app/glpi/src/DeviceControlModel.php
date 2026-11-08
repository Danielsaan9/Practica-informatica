<?php

/// Class DeviceControlModel
class DeviceControlModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device control model', 'Device control models', $nb);
    }
}
