<?php

/**
 * Relation between item and devices
 **/
class Item_DeviceControl extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceControl::class;
    public static ?string $items_id_2 = 'devicecontrols_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {

        return ['serial' => parent::getSpecificities('serial'),
            'otherserial' => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id' => parent::getSpecificities('states_id'),
            'busID'  => parent::getSpecificities('busID'),
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'serial' => 'equal',
            'busID' => 'equal',
        ];
    }
}
