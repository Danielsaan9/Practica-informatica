<?php

class RuleDictionnaryOperatingSystemServicePackCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_operatingsystemservicepacks";
    public string $menu_option = "os_sp";

    public function getTitle()
    {
        return __('Dictionary of service packs');
    }
}
