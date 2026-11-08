<?php

/// Class DeviceMemoryType
class DeviceMemoryType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Memory type', 'Memory types', $nb);
    }
}
