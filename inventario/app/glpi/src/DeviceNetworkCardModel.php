<?php

/// Class DeviceNetworkCardModel
class DeviceNetworkCardModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Network card model', 'Network card models', $nb);
    }
}
