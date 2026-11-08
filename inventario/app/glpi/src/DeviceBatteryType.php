<?php

class DeviceBatteryType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Battery type', 'Battery types', $nb);
    }
}
