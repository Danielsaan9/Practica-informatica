<?php

class DeviceFirmware extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceFirmware', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Firmware', 'Firmware', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'devicefirmwaretypes_id',
                    'label' => _n('Type', 'Types', 1),
                    'type'  => 'dropdownValue',
                ],
                [
                    'name'   => 'date',
                    'label'  => __('Release date'),
                    'type'   => 'date',
                ],
                [
                    'name'   => 'version',
                    'label'  => _n('Version', 'Versions', 1),
                    'type'   => 'text',
                ],
                [
                    'name'   => 'devicefirmwaremodels_id',
                    'label'  => _n('Model', 'Models', 1),
                    'type'   => 'dropdownValue',
                ],
            ]
        );
    }

    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '11',
            'table'              => static::getTable(),
            'field'              => 'date',
            'name'               => __('Release date'),
            'datatype'           => 'date',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => 'glpi_devicefirmwaremodels',
            'field'              => 'name',
            'name'               => _n('Model', 'Models', 1),
            'datatype'           => 'dropdown',
        ];

        $tab[] = [
            'id'                 => '13',
            'table'              => 'glpi_devicefirmwaretypes',
            'field'              => 'name',
            'name'               => _n('Type', 'Types', 1),
            'datatype'           => 'dropdown',
        ];

        $tab[] = [
            'id'                 => '14',
            'table'              => 'glpi_devicefirmwares',
            'field'              => 'version',
            'name'               => _n('Version', 'Versions', 1),
        ];

        return $tab;
    }

    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param array $main_joinparams
     * @return array
     */
    public static function rawSearchOptionsToAdd($itemtype, $main_joinparams)
    {
        $tab = [];

        //SO defined from glpi_devicefirmwares table
        $tab[] = [
            'id'                 => '1313',
            'table'              => 'glpi_devicefirmwares',
            'field'              => 'designation',
            'name'               => self::getTypeName(1),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'massiveaction'      => false,
            'datatype'           => 'string',
            'joinparams'         => [
                'beforejoin'         => [
                    'table'              => 'glpi_items_devicefirmwares',
                    'joinparams'         => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '1314',
            'table'              => 'glpi_devicefirmwares',
            'field'              => 'version',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), _n('Version', 'Versions', 1)),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'massiveaction'      => false,
            'datatype'           => 'string',
            'joinparams'         => [
                'beforejoin'         => [
                    'table'              => 'glpi_items_devicefirmwares',
                    'joinparams'         => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '1315',
            'table'              => 'glpi_devicefirmwaretypes',
            'field'              => 'name',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), _n('Type', 'Types', 1)),
            'massiveaction'      => false,
            'datatype'           => 'dropdown',
            'joinparams'         => [
                'beforejoin' => [
                    'table'      => self::getTable(),
                    'joinparams' => [
                        'beforejoin' => [
                            'table'      => Item_DeviceFirmware::getTable(),
                            'joinparams' => ['jointype' => 'itemtype_item'],
                        ],
                    ],
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '1316',
            'table'              => 'glpi_devicefirmwaremodels',
            'field'              => 'name',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), _n('Model', 'Models', 1)),
            'massiveaction'      => false,
            'datatype'           => 'dropdown',
            'joinparams'         => [
                'beforejoin' => [
                    'table'      => self::getTable(),
                    'joinparams' => [
                        'beforejoin' => [
                            'table'      => Item_DeviceFirmware::getTable(),
                            'joinparams' => ['jointype' => 'itemtype_item'],
                        ],
                    ],
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '1317',
            'table'              => 'glpi_manufacturers',
            'field'              => 'name',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), Manufacturer::getTypeName(1)),
            'massiveaction'      => false,
            'datatype'           => 'dropdown',
            'joinparams'         => [
                'beforejoin' => [
                    'table'      => self::getTable(),
                    'joinparams' => [
                        'beforejoin' => [
                            'table'      => Item_DeviceFirmware::getTable(),
                            'joinparams' => ['jointype' => 'itemtype_item'],
                        ],
                    ],
                ],
            ],
        ];

        //SO defined from relation (glpi_items_devicefirmwares) table
        $tab[] = [
            'id'                 => '1318',
            'table'              => 'glpi_items_devicefirmwares',
            'field'              => 'serial',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Serial Number')),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'datatype'           => 'string',
            'massiveaction'      => false,
            'joinparams'         => $main_joinparams,
        ];

        $tab[] = [
            'id'                 => '1319',
            'table'              => 'glpi_items_devicefirmwares',
            'field'              => 'otherserial',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Inventory number')),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'datatype'           => 'string',
            'massiveaction'      => false,
            'joinparams'         => $main_joinparams,
        ];

        return $tab;
    }

    public static function getHTMLTableHeader(
        $itemtype,
        HTMLTableBase $base,
        ?HTMLTableSuperHeader $super = null,
        ?HTMLTableHeader $father = null,
        array $options = []
    ) {
        global $CFG_GLPI;
        $column = parent::getHTMLTableHeader($itemtype, $base, $super, $father, $options);

        if ($column == $father) {
            return $father;
        }

        if (in_array($itemtype, $CFG_GLPI['itemdevicefirmware_types'])) {
            Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
            $base->addHeader('devicefirmware_type', _sn('Type', 'Types', 1), $super, $father);
            $base->addHeader('version', _sn('Version', 'Versions', 1), $super, $father);
            $base->addHeader('date', __s('Release date'), $super, $father);
        }
    }

    public function getHTMLTableCellForItem(
        ?HTMLTableRow $row = null,
        ?CommonDBTM $item = null,
        ?HTMLTableCell $father = null,
        array $options = []
    ) {
        global $CFG_GLPI;
        $column = parent::getHTMLTableCellForItem($row, $item, $father, $options);

        if ($column == $father) {
            return $father;
        }

        if (in_array($item::class, $CFG_GLPI['itemdevicefirmware_types'], true)) {
            Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);

            if ($this->fields["devicefirmwaretypes_id"]) {
                $row->addCell(
                    $row->getHeaderByName('devicefirmware_type'),
                    htmlescape(Dropdown::getDropdownName("glpi_devicefirmwaretypes", $this->fields["devicefirmwaretypes_id"])),
                    $father
                );
            }
            $row->addCell(
                $row->getHeaderByName('version'),
                htmlescape($this->fields["version"]),
                $father
            );

            if ($this->fields["date"]) {
                $row->addCell(
                    $row->getHeaderByName('date'),
                    htmlescape(Html::convDate($this->fields["date"])),
                    $father
                );
            }
        }
        return null;
    }

    public function getImportCriteria()
    {
        return [
            'designation'              => 'equal',
            'devicefirmwaretypes_id'   => 'equal',
            'manufacturers_id'         => 'equal',
            'version'                  => 'equal',
        ];
    }

    public static function getIcon()
    {
        return "ti ti-cpu";
    }
}
