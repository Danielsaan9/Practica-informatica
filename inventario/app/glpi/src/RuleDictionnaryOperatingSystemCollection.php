<?php

class RuleDictionnaryOperatingSystemCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_operatingsystems";
    public string $menu_option = "os";

    public function getTitle()
    {
        return __('Dictionary of operating systems');
    }
}
