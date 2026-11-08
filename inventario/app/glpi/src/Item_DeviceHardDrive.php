<?php

/**
 * Relation between item and devices
 **/
class Item_DeviceHardDrive extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceHardDrive::class;
    public static ?string $items_id_2 = 'deviceharddrives_id';

    protected static bool $notable = false;

    public static function getSpecificities($specif = '')
    {

        return [
            'capacity' => [
                'long name'  => sprintf(__('%1$s (%2$s)'), __('Capacity'), __('Mio')),
                'short name' => __('Capacity'),
                'size'       => 10,
                'id'         => 20,
                'datatype'   => 'integer',
            ],
            'serial'   => parent::getSpecificities('serial'),
            'otherserial' => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id' => parent::getSpecificities('states_id'),
            'busID'    => parent::getSpecificities('busID'),
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'serial' => 'equal',
            'capacity' => 'delta:100',
            'busID' => 'equal',
        ];
    }
}
