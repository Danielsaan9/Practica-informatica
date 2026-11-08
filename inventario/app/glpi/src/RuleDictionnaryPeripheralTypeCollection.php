<?php

class RuleDictionnaryPeripheralTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_peripheraltypes";
    public string $menu_option = "type.peripheral";

    public function getTitle()
    {
        return __('Dictionary of device types');
    }
}
