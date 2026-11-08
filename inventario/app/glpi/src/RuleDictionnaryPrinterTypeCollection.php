<?php

class RuleDictionnaryPrinterTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_printertypes";
    public string $menu_option = "type.printer";

    public function getTitle()
    {
        return __('Dictionary of printer types');
    }
}
