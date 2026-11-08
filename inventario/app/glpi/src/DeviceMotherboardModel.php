<?php

/// Class DeviceMotherboardModel
class DeviceMotherboardModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('System board model', 'System board models', $nb);
    }
}
