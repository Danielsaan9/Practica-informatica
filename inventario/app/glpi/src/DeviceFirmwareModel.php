<?php

class DeviceFirmwareModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device firmware model', 'Device firmware models', $nb);
    }
}
