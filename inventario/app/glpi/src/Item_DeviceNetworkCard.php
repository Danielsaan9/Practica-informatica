<?php

/**
 * @since 0.84
 */


/**
 * Relation between item and devices
 **/
class Item_DeviceNetworkCard extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceNetworkCard::class;
    public static ?string $items_id_2 = 'devicenetworkcards_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {

        return ['mac'    => ['long name'  => __('MAC address'),
            'short name' => __('MAC address'),
            'size'       => 18,
            'id'         => 20,
            'datatype'   => 'mac',
        ],
            'serial' => parent::getSpecificities('serial'),
            'otherserial' => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id' => parent::getSpecificities('states_id'),
            'busID'  => parent::getSpecificities('busID'),
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'mac' => 'equal',
            'serial' => 'equal',
            'busID' => 'equal',
        ];
    }
}
