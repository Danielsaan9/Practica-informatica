<?php

/*
 * @since 9.2
 */
class DeviceSensor extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceSensor', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Sensor', 'Sensors', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'devicesensortypes_id',
                    'label' => _n('Type', 'Types', 1),
                    'type'  => 'dropdownValue',
                ],
            ]
        );
    }

    public function rawSearchOptions()
    {
        $tab                 = parent::rawSearchOptions();

        $tab[] = ['id'       => '12',
            'table'    => 'glpi_devicesensortypes',
            'field'    => 'name',
            'name'     => _n('Type', 'Types', 1),
            'datatype' => 'dropdown',
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
            case 'Peripheral':
                Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
                $base->addHeader('devicesensor_type', _sn('Type', 'Types', 1), $super, $father);
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
            case Peripheral::class:
                Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
                break;
        }
        return null;
    }

    /**
     * Criteria used for import function
     */
    public function getImportCriteria()
    {
        return [
            'designation'          => 'equal',
            'manufacturers_id'     => 'equal',
            'devicesensortypes_id' => 'equal',
            'locations_id'         => 'equal',
        ];
    }
}
