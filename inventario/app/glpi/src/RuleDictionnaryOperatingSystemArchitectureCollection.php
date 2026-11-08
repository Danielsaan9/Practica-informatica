<?php

class RuleDictionnaryOperatingSystemArchitectureCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_operatingsystemarchitectures";
    public string $menu_option = "os_arch";

    public function getTitle()
    {
        return __('Dictionary of operating system architectures');
    }
}
