<?php

class RuleDictionnaryMonitorTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_monitortypes";
    public string $menu_option = "type.monitor";

    public function getTitle()
    {
        return __('Dictionary of monitor types');
    }
}
