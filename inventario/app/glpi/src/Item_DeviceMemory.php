<?php

/**
 * @since 0.84
 */


/**
 * Relation between item and devices
 **/
class Item_DeviceMemory extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceMemory::class;
    public static ?string $items_id_2 = 'devicememories_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {

        return [
            'size'   => [
                'long name'  => sprintf(__('%1$s (%2$s)'), __('Size'), __('Mio')),
                'short name' => __('Size'),
                'size'       => 10,
                'id'         => 20,
                'datatype'   => 'integer',
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
            'size' => 'delta:10',
            'serial' => 'equal',
            //'busID' => 'equal',
        ];
    }
}
