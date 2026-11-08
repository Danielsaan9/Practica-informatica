<?php

/// Class DeviceSimcard
class DeviceSimcard extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceSimcard', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Simcard', 'Simcards', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'devicesimcardtypes_id',
                    'label' => _n('Type', 'Types', 1),
                    'type'  => 'dropdownValue',
                ],
                [
                    'name'  => 'voltage',
                    'label' => __('Voltage'),
                    'type'  => 'integer',
                    'min'   => 0,
                    'unit'  => 'mV',
                ],
                [
                    'name'  => 'allow_voip',
                    'label' => __('Allow VOIP'),
                    'type'  => 'bool',
                ],
            ]
        );
    }

    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'voltage',
            'name'               => __('Voltage'),
            'datatype'           => 'integer',
        ];

        $tab[] = [
            'id'                 => '13',
            'table'              => 'glpi_devicesimcardtypes',
            'field'              => 'name',
            'name'               => _n('Type', 'Types', 1),
            'datatype'           => 'dropdown',
        ];

        $tab[] = [
            'id'                 => '14',
            'table'              => static::getTable(),
            'field'              => 'allow_voip',
            'name'               => __('Allow VOIP'),
            'datatype'           => 'bool',
        ];

        return $tab;
    }

    public function getImportCriteria()
    {
        return [
            'designation'             => 'equal',
            'manufacturers_id'        => 'equal',
            'devicesimcardtypes_id'   => 'equal',
        ];
    }

    public static function getIcon()
    {
        return "ti ti-device-sim";
    }

    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param array<string, mixed> $main_joinparams
     * @return array<int|string, mixed>
     */
    public static function rawSearchOptionsToAdd($itemtype, $main_joinparams): array
    {

        $tab = [];

        $tab[] = [
            'id'            => '1344',
            'table'         => 'glpi_items_devicesimcards',
            'field'         => 'msin',
            'name'          => sprintf(__('%1$s %2$s: %3$s'), self::getTypeName(1), __('items'), __('MSIN')),
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'text',
            'joinparams'    => $main_joinparams,
        ];

        $tab[] = [
            'id'            => '1345',
            'table'         => 'glpi_devicesimcards',
            'field'         => 'designation',
            'name'          => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Designation')),
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'text',
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_items_devicesimcards',
                    'joinparams' => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'            => '1346',
            'table'         => 'glpi_manufacturers',
            'field'         => 'name',
            'name'          => sprintf(__('%1$s: %2$s'), self::getTypeName(1), Manufacturer::getTypeName(1)),
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'dropdown',
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_devicesimcards',
                    'joinparams' => [
                        'beforejoin' => [
                            'table'      => 'glpi_items_devicesimcards',
                            'joinparams' => $main_joinparams,
                        ],
                    ],
                ],
            ],
        ];

        $tab[] = [
            'id'            => '1347',
            'table'         => 'glpi_devicesimcards',
            'field'         => 'voltage',
            'name'          => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Voltage')),
            'forcegroupby'  => false,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'integer',
            'unit'          => 'mV',
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_items_devicesimcards',
                    'joinparams' => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'            => '1348',
            'table'         => 'glpi_devicesimcardtypes',
            'field'         => 'name',
            'name'          => sprintf(__('%1$s: %2$s'), self::getTypeName(1), DeviceSimcardType::getTypeName(1)),
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'dropdown',
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_devicesimcards',
                    'joinparams' => [
                        'beforejoin' => [
                            'table'      => 'glpi_items_devicesimcards',
                            'joinparams' => $main_joinparams,
                        ],
                    ],
                ],
            ],

        ];

        $tab[] = [
            'id'            => '1349',
            'table'         => 'glpi_devicesimcards',
            'field'         => 'allow_voip',
            'name'          => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Allow VOIP')),
            'forcegroupby'  => false,
            'usehaving'     => true,
            'massiveaction' => false,
            'datatype'      => 'bool',
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_items_devicesimcards',
                    'joinparams' => $main_joinparams,
                ],
            ],
        ];



        return $tab;
    }
}
