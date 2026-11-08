<?php

class RuleDictionnaryPeripheralModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_peripheralmodels";
    public string $menu_option = "model.peripheral";

    public function getTitle()
    {
        return __('Dictionary of device models');
    }
}
