<?php

class DeviceCameraModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];

    public static function getTypeName($nb = 0)
    {
        return _n('Device camera model', 'Device camera models', $nb);
    }
}
