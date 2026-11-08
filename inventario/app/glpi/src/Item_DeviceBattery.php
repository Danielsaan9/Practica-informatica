<?php

/**
 * Relation between item and devices
 **/
class Item_DeviceBattery extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceBattery::class;
    public static ?string $items_id_2 = 'devicebatteries_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {
        return [
            'serial'             => parent::getSpecificities('serial'),
            'otherserial'        => parent::getSpecificities('otherserial'),
            'locations_id'       => parent::getSpecificities('locations_id'),
            'states_id'          => parent::getSpecificities('states_id'),
            'manufacturing_date' => [
                'long name' => __('Manufacturing date'),
                'short name' => _n('Date', 'Dates', 1),
                'size'       => 10,
                'datatype'   => 'date',
                'id'         => 20,
            ],
            'real_capacity' => [
                'long name' => __('Real capacity (mWh)'),
                'short name' => __('Real capacity'),
                'size'       => 10,
                'id'         => 21,
                'datatype'   => 'progressbar',
                'max'       => 'capacity',    // Field used to represent 100% capacity value
            ],
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'serial' => 'equal',
            //'real_capacity' => 'delta:10'
        ];
    }
}
