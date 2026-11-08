<?php

class RuleDictionnaryPhoneTypeCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_phonetypes";
    public string $menu_option = "type.phone";

    public function getTitle()
    {
        return __('Dictionary of phone types');
    }
}
