<?php

/// Class DeviceGraphicCard
class DeviceGraphicCard extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceGraphicCard', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Graphics card', 'Graphics cards', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'chipset',
                    'label' => __('Chipset'),
                    'type'  => 'text',
                ],
                [
                    'name'  => 'memory_default',
                    'label' => __('Memory by default'),
                    'type'  => 'integer',
                    'min'  => 0,
                    'unit'  => __('Mio'),
                ],
                [
                    'name'  => 'interfacetypes_id',
                    'label' => __('Interface'),
                    'type'  => 'dropdownValue',
                ],
                [
                    'name'  => 'none',
                    'label' => RegisteredID::getTypeName(Session::getPluralNumber()),
                    'type'  => 'registeredIDChooser',
                ],
                [
                    'name'  => 'devicegraphiccardmodels_id',
                    'label' => _n('Model', 'Models', 1),
                    'type'  => 'dropdownValue',
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
            'field'              => 'chipset',
            'name'               => __('Chipset'),
            'datatype'           => 'string',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'memory_default',
            'name'               => __('Memory by default'),
            'datatype'           => 'integer',
        ];

        $tab[] = [
            'id'                 => '14',
            'table'              => 'glpi_interfacetypes',
            'field'              => 'name',
            'name'               => __('Interface'),
            'datatype'           => 'dropdown',
        ];

        $tab[] = [
            'id'                 => '15',
            'table'              => 'glpi_devicegraphiccardmodels',
            'field'              => 'name',
            'name'               => _n('Model', 'Models', 1),
            'datatype'           => 'dropdown',
        ];

        return $tab;
    }

    /**
     * @since 0.85
     * @param array $input
     *
     * @return array
     **/
    public function prepareInputForAddOrUpdate($input)
    {
        foreach (['memory_default'] as $field) {
            if (isset($input[$field]) && !is_numeric($input[$field])) {
                $input[$field] = 0;
            }
        }
        return $input;
    }

    public function prepareInputForAdd($input)
    {
        return $this->prepareInputForAddOrUpdate($input);
    }

    public function prepareInputForUpdate($input)
    {
        return $this->prepareInputForAddOrUpdate($input);
    }

    public static function getHTMLTableHeader(
        $itemtype,
        HTMLTableBase $base,
        ?HTMLTableSuperHeader $super = null,
        ?HTMLTableHeader $father = null,
        array $options = []
    ) {
        $column = parent::getHTMLTableHeader($itemtype, $base, $super, $father, $options);

        if ($column == $father) {
            return $father;
        }

        switch ($itemtype) {
            case Computer::class:
                Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
                InterfaceType::getHTMLTableHeader(self::class, $base, $super, $father, $options);
                $base->addHeader('devicegraphiccard_chipset', __s('Chipset'), $super, $father);
                break;
        }
    }

    public function getHTMLTableCellForItem(
        ?HTMLTableRow $row = null,
        ?CommonDBTM $item = null,
        ?HTMLTableCell $father = null,
        array $options = []
    ) {
        $column = parent::getHTMLTableCellForItem($row, $item, $father, $options);

        if ($column == $father) {
            return $father;
        }

        $cell = null;
        switch ($item::class) {
            case Computer::class:
                Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
                InterfaceType::getHTMLTableCellsForItem($row, $this, null, $options);

                if (!empty($this->fields["chipset"])) {
                    $cell = $row->addCell(
                        $row->getHeaderByName('devicegraphiccard_chipset'),
                        htmlescape($this->fields["chipset"]),
                        $father
                    );
                }
                break;
        }
        return $cell;
    }

    public function getImportCriteria()
    {
        return [
            'designation' => 'equal',
            'chipset'  => 'equal',
        ];
    }

    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param array $main_joinparams
     * @return array
     */
    public static function rawSearchOptionsToAdd($itemtype, $main_joinparams)
    {
        $tab = [];

        $tab[] = [
            'id'                 => '13',
            'table'              => 'glpi_devicegraphiccards',
            'field'              => 'designation',
            'name'               => static::getTypeName(1),
            'forcegroupby'       => true,
            'massiveaction'      => false,
            'datatype'           => 'string',
            'joinparams'         => [
                'beforejoin'         => [
                    'table'              => 'glpi_items_devicegraphiccards',
                    'joinparams'         => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '1322',
            'table'              => 'glpi_items_devicegraphiccards',
            'field'              => 'serial',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Serial Number')),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'datatype'           => 'string',
            'massiveaction'      => false,
            'joinparams'         => $main_joinparams,
        ];

        $tab[] = [
            'id'                 => '1323',
            'table'              => 'glpi_items_devicegraphiccards',
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
}
