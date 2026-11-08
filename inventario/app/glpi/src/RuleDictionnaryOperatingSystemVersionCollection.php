<?php

class RuleDictionnaryOperatingSystemVersionCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_operatingsystemversions";
    public string $menu_option = "os_version";

    public function getTitle()
    {
        return __('Dictionary of operating system versions');
    }
}
