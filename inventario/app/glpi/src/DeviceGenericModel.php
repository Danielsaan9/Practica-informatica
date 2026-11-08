<?php

/// Class DeviceGenericModel
class DeviceGenericModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device generic model', 'Device generic models', $nb);
    }
}
