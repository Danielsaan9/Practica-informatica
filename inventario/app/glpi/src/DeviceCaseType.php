<?php

/// Class DeviceCaseType (Interface is a reserved keyword)
class DeviceCaseType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return  _n('Case type', 'Case types', $nb);
    }
}
