<?php

/// Class DeviceProcessorModel
class DeviceProcessorModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device processor model', 'Device processor models', $nb);
    }
}
