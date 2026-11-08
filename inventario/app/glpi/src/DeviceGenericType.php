<?php

/// Class DeviceGenericType
class DeviceGenericType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Generic type', 'Generic types', $nb);
    }
}
