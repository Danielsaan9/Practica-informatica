<?php

class RuleDictionnaryNetworkEquipmentTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_networkequipmenttypes";
    public string $menu_option = "type.networking";

    public function getTitle()
    {
        return __('Dictionary of network equipment types');
    }
}
