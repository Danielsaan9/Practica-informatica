<?php

/**
 * @since 9.2
 */
class Item_DeviceSensor extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceSensor::class;
    public static ?string $items_id_2 = 'devicesensors_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {
        return ['serial'       => parent::getSpecificities('serial'),
            'otherserial'  => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id'    => parent::getSpecificities('states_id'),
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'serial' => 'equal',
        ];
    }
}
