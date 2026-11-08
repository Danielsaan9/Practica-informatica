<?php

/**
 * @since 9.2
 */
class DeviceSensorType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Sensor type', 'Sensor types', $nb);
    }
}
