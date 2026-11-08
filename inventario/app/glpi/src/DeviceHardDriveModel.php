<?php

/// Class DeviceHardDriveModel
class DeviceHardDriveModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device hard drive model', 'Device hard drive models', $nb);
    }
}
