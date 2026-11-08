<?php

/**
 * DeviceNetworkCard Class
 **/
class DeviceNetworkCard extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceNetworkCard', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Network card', 'Network cards', $nb);
    }

    /**
     * Criteria used for import function
     *
     * @since 0.84
     **/
    public function getImportCriteria()
    {
        return [
            'designation' => 'equal',
            'bandwidth' => 'equal',
        ];
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'mac_default',
                    'label' => __('MAC address by default'),
                    'type'  => 'text',
                ],
                [
                    'name'  => 'bandwidth',
                    'label' => __('Flow'),
                    'type'  => 'text',
                ],
                [
                    'name'  => 'devicenetworkcardmodels_id',
                    'label' => _n('Model', 'Models', 1),
                    'type'  => 'dropdownValue',
                ],
                [
                    'name'  => 'none',
                    'label' => RegisteredID::getTypeName(Session::getPluralNumber()),
                    'type'  => 'registeredIDChooser',
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
            'field'              => 'mac_default',
            'name'               => __('MAC address by default'),
            'datatype'           => 'mac',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'bandwidth',
            'name'               => __('Flow'),
            'datatype'           => 'string',
        ];

        $tab[] = [
            'id'                 => '13',
            'table'              => 'glpi_devicenetworkcardmodels',
            'field'              => 'name',
            'name'               => _n('Model', 'Models', 1),
            'datatype'           => 'dropdown',
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

        $column_name = self::class;

        if (isset($options['dont_display'][$column_name])) {
            return;
        }

        if (in_array($itemtype, NetworkPort::getNetworkPortInstantiations(), true)) {
            $base->addHeader($column_name, __s('Interface'), $super, $father);
        } else {
            $column = parent::getHTMLTableHeader($itemtype, $base, $super, $father, $options);
            if ($column == $father) {
                return $father;
            }
            Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
            $base->addHeader('devicenetworkcard_bandwidth', __s('Flow'), $super, $father);
        }
    }

    /**
     * @param HTMLTableRow|null $row
     * @param CommonDBTM|null $item
     * @param HTMLTableCell|null $father
     * @param array $options
     * @return void
     */
    public static function getHTMLTableCellsForItem(
        ?HTMLTableRow $row = null,
        ?CommonDBTM $item = null,
        ?HTMLTableCell $father = null,
        array $options = []
    ) {

        $column_name = self::class;

        if (isset($options['dont_display'][$column_name])) {
            return;
        }

        if ($item === null) {
            if ($father === null) {
                return;
            }
            $item = $father->getItem();
            if ($item === false) {
                return;
            }
        }

        if (in_array($item::class, NetworkPort::getNetworkPortInstantiations())) {
            $link = new Item_DeviceNetworkCard();
            if ($link->getFromDB($item->fields['items_devicenetworkcards_id'])) {
                $device = $link->getOnePeer(1);
                if ($device) {
                    $row->addCell($row->getHeaderByName($column_name), $device->getLink(), $father);
                }
            }
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
                if ($this->fields["bandwidth"]) {
                    $cell = $row->addCell(
                        $row->getHeaderByName('devicenetworkcard_bandwidth'),
                        htmlescape($this->fields["bandwidth"]),
                        $father
                    );
                }
                break;
        }
        return $cell;
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
            'id'                 => '112',
            'table'              => 'glpi_devicenetworkcards',
            'field'              => 'designation',
            'name'               => NetworkInterface::getTypeName(1),
            'forcegroupby'       => true,
            'massiveaction'      => false,
            'datatype'           => 'string',
            'joinparams'         => [
                'beforejoin'         => [
                    'table'              => 'glpi_items_devicenetworkcards',
                    'joinparams'         => $main_joinparams,
                ],
            ],
        ];

        $tab[] = [
            'id'                 => '113',
            'table'              => 'glpi_items_devicenetworkcards',
            'field'              => 'mac',
            'name'               => __('MAC address'),
            'forcegroupby'       => true,
            'massiveaction'      => false,
            'datatype'           => 'string',
            'joinparams'         => $main_joinparams,
        ];

        $tab[] = [
            'id'                 => '1330',
            'table'              => 'glpi_items_devicenetworkcards',
            'field'              => 'serial',
            'name'               => sprintf(__('%1$s: %2$s'), self::getTypeName(1), __('Serial Number')),
            'forcegroupby'       => true,
            'usehaving'          => true,
            'datatype'           => 'string',
            'massiveaction'      => false,
            'joinparams'         => $main_joinparams,
        ];

        $tab[] = [
            'id'                 => '1331',
            'table'              => 'glpi_items_devicemotherboards',
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


    public static function getIcon()
    {
        return NetworkPort::getIcon();
    }
}
