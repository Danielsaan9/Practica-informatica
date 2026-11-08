<?php

/**
 * Relation between item and devices
 **/
class Item_DeviceProcessor extends Item_Devices
{
    public static ?string $itemtype_2 = DeviceProcessor::class;
    public static ?string $items_id_2 = 'deviceprocessors_id';

    protected static bool $notable = false;


    public static function getSpecificities($specif = '')
    {

        return [
            'frequency' => [
                'long name'  => sprintf(__('%1$s (%2$s)'), __('Frequency'), __('MHz')),
                'short name' => sprintf(__('%1$s (%2$s)'), __('Frequency'), __('MHz')),
                'size'       => 10,
                'id'         => 20,
                'datatype'   => 'integer',
            ],
            'serial'    => parent::getSpecificities('serial'),
            'otherserial' => parent::getSpecificities('otherserial'),
            'locations_id' => parent::getSpecificities('locations_id'),
            'states_id' => parent::getSpecificities('states_id'),
            'nbcores'   => [
                'long name'  => __('Number of cores'),
                'short name' => __('Cores'),
                'size'       => 2,
                'id'         => 21,
                'datatype'   => 'integer',
            ],
            'nbthreads' => [
                'long name'  => __('Number of threads'),
                'short name' => __('Threads'),
                'size'       => 2,
                'id'         => 22,
                'datatype'   => 'integer',
            ],
            'busID'     => parent::getSpecificities('busID'),
        ];
    }

    public function getImportCriteria(): array
    {
        return [
            'serial' => 'equal',
            'frequency' => 'delta:100',
            //'nbcores' => 'equal',
            //'nbthreads' => 'equal',
            'busID' => 'equal',
        ];
    }
}
