<?php

/// Class DeviceDriveModel
class DeviceDriveModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device drive model', 'Device drive models', $nb);
    }
}
