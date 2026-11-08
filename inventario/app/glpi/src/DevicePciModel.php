<?php

/// Class DevicePciModel
class DevicePciModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Other component model', 'Other component models', $nb);
    }
}
