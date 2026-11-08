<?php

/// Class DeviceSoundCardModel
class DeviceSoundCardModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device sound card model', 'Device sound card models', $nb);
    }
}
