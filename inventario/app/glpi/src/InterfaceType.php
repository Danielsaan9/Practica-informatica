<?php

/// Class InterfaceType (Interface is a reserved keyword)
class InterfaceType extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Interface type (Hard drive...)', 'Interface types (Hard drive...)', $nb);
    }


    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param HTMLTableBase $base
     * @param ?HTMLTableSuperHeader $super
     * @param ?HTMLTableHeader $father
     * @param array $options
     *
     * @return void
     **/
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

        $base->addHeader($column_name, __s('Interface'), $super, $father);
    }


    /**
     * @param ?HTMLTableRow $row
     * @param ?CommonDBTM $item
     * @param ?HTMLTableCell $father
     * @param array $options
     *
     * @return void
     **/
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

        if ($item->fields["interfacetypes_id"]) {
            $row->addCell(
                $row->getHeaderByName($column_name),
                htmlescape(Dropdown::getDropdownName("glpi_interfacetypes", $item->fields["interfacetypes_id"]))
            );
        }
    }
}
