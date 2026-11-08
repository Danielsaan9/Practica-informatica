<?php

/// Class DeviceDrive
class DeviceDrive extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceDrive', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Drive', 'Drives', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [['name'  => 'is_writer',
                'label' => __('Writing ability'),
                'type'  => 'bool',
            ],
                ['name'  => 'speed',
                    'label' => __('Speed'),
                    'type'  => 'text',
                ],
                ['name'  => 'interfacetypes_id',
                    'label' => __('Interface'),
                    'type'  => 'dropdownValue',
                ],
                ['name'  => 'devicedrivemodels_id',
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
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'is_writer',
            'name'               => __('Writing ability'),
            'datatype'           => 'bool',
        ];

        $tab[] = [
            'id'                 => '13',
            'table'              => static::getTable(),
            'field'              => 'speed',
            'name'               => __('Speed'),
            'datatype'           => 'string',
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
            'table'              => 'glpi_devicedrivemodels',
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

        $column = parent::getHTMLTableHeader($itemtype, $base, $super, $father, $options);

        if ($column == $father) {
            return $father;
        }

        switch ($itemtype) {
            case 'Computer':
                Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
                $base->addHeader('devicedrive_writer', __s('Writing ability'), $super, $father);
                $base->addHeader('devicedrive_speed', __s('Speed'), $super, $father);
                InterfaceType::getHTMLTableHeader(self::class, $base, $super, $father, $options);
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

        switch ($item::class) {
            case 'Computer':
                Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
                if ($this->fields["is_writer"]) {
                    $row->addCell(
                        $row->getHeaderByName('devicedrive_writer'),
                        htmlescape(Dropdown::getYesNo($this->fields["is_writer"])),
                        $father
                    );
                }

                if ($this->fields["speed"]) {
                    $row->addCell(
                        $row->getHeaderByName('devicedrive_speed'),
                        htmlescape($this->fields["speed"]),
                        $father
                    );
                }

                InterfaceType::getHTMLTableCellsForItem($row, $this, null, $options);
        }
        return null;
    }

    public function getImportCriteria()
    {
        return [
            'designation'       => 'equal',
            'manufacturers_id'  => 'equal',
            'interfacetypes_id' => 'equal',
        ];
    }

    public static function getIcon()
    {
        return "ti ti-server-2";
    }
}
