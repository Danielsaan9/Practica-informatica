<?php

class RuleDictionnaryComputerTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_computertypes";
    public string $menu_option = "type.computer";


    public function getTitle()
    {
        return __('Dictionary of computer types');
    }
}
