<?php

/**
 * @since 9.2
 */


class DeviceSimcardType extends CommonDeviceType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Simcard type', 'Simcard types', $nb);
    }
}
