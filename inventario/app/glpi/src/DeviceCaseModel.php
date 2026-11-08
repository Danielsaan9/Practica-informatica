<?php

/// Class DeviceCaseModel
class DeviceCaseModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device case model', 'Device case models', $nb);
    }
}
