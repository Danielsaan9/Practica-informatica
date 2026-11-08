<?php

class RuleDictionnaryComputerModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_computermodels";
    public string $menu_option = "model.computer";

    public function getTitle()
    {
        return __('Dictionary of computer models');
    }
}
