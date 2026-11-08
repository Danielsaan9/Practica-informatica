<?php

/// Class DeviceGeneric
class DeviceGeneric extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceGeneric', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Generic device', 'Generic devices', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [['name'  => 'devicegenerictypes_id',
                'label' => _n('Type', 'Types', 1),
                'type'  => 'dropdownValue',
            ],
            ]
        );
    }

    public function rawSearchOptions()
    {
        $tab                 = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '12',
            'table'              => 'glpi_devicegenerictypes',
            'field'              => 'name',
            'name'               => _n('Type', 'Types', 1),
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
                $base->addHeader('devicegenerictypes_id', _sn('Type', 'Types', 1), $super, $father);
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
            case Computer::class:
                Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
                if ($this->fields["devicegenerictypes_id"]) {
                    $type_name = Dropdown::getDropdownName(
                        "glpi_devicegenerictypes",
                        $this->fields["devicegenerictypes_id"]
                    );
                    $row->addCell(
                        $row->getHeaderByName('devicegenerictypes_id'),
                        htmlescape($type_name)
                    );
                }
                break;
        }
        return null;
    }

    public function getImportCriteria()
    {
        return [
            'designation'       => 'equal',
            'manufacturers_id'  => 'equal',
            'devicegenerictypes_id' => 'equal',
            'locations_id'      => 'equal',
        ];
    }
}
