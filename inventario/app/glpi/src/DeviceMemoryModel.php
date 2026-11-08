<?php

/// Class DeviceMemoryModel
class DeviceMemoryModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device memory model', 'Device memory models', $nb);
    }
}
