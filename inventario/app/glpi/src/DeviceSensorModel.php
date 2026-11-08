<?php

/**
 * @since 9.2
 */
class DeviceSensorModel extends CommonDeviceModel
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];


    public static function getTypeName($nb = 0)
    {
        return _n('Device sensor model', 'Device sensor models', $nb);
    }
}
