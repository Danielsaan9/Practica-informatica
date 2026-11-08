<?php

class DeviceFirmwareType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Firmware type', 'Firmware types', $nb);
    }
}
