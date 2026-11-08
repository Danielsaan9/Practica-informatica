<?php

class RuleDictionnaryNetworkEquipmentModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_networkequipmentmodels";
    public string $menu_option = "model.networking";

    public function getTitle()
    {
        return __('Dictionary of networking equipment models');
    }
}
