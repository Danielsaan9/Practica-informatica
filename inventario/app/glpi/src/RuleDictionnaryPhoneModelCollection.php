<?php

class RuleDictionnaryPhoneModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_phonemodels";
    public string $menu_option = "model.phone";

    public function getTitle()
    {
        return __('Dictionary of phone models');
    }
}
