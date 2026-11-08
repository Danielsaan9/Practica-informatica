<?php

class RuleDictionnaryMonitorModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_monitormodels";
    public string $menu_option = "model.monitor";


    public function getTitle()
    {
        return __('Dictionary of computer models');
    }
}
