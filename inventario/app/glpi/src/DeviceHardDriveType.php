<?php

class DeviceHardDriveType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Device hard drive type', 'Device hard drive types', $nb);
    }
}
