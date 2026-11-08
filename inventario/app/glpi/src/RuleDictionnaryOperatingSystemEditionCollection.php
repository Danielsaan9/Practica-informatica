<?php

class RuleDictionnaryOperatingSystemEditionCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_operatingsystemeditions";
    public string $menu_option = "os_edition";

    public function getTitle()
    {
        return __('Dictionary of operating system editions');
    }
}
