<?php

/**
 * DeviceControl Class
 **/
class DeviceControl extends CommonDevice
{
    protected static array $forward_entity_to = ['Item_DeviceControl', 'Infocom'];

    public static function getTypeName($nb = 0)
    {
        return _n('Controller', 'Controllers', $nb);
    }

    public function getAdditionalFields()
    {
        return array_merge(
            parent::getAdditionalFields(),
            [
                [
                    'name'  => 'is_raid',
                    'label' => __('RAID'),
                    'type'  => 'bool',
                ],
                [
                    'name'  => 'interfacetypes_id',
                    'label' => __('Interface'),
                    'type'  => 'dropdownValue',
                ],
                [
                    'name'  => 'devicecontrolmodels_id',
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
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'is_raid',
            'name'               => __('RAID'),
            'datatype'           => 'bool',
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
            'table'              => 'glpi_devicecontrolmodels',
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
            case Computer::class:
                Manufacturer::getHTMLTableHeader(self::class, $base, $super, $father, $options);
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
            case Computer::class:
                Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
                InterfaceType::getHTMLTableCellsForItem($row, $this, null, $options);
        }
        return $column;
    }

    public static function getIcon()
    {
        return "ti ti-cpu";
    }
}
