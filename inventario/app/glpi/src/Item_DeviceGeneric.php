<?php

class Item_DeviceGeneric extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceGeneric::class;
    public static ?string $items_id_2 = 'devicegenerics_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {
        return ['serial' => parent::getSpecificities('serial'),
            'otherserial' => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id' => parent::getSpecificities('states_id'),
        ];
    }
}
