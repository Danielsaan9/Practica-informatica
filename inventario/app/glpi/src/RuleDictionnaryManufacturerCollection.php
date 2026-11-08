<?php

class RuleDictionnaryManufacturerCollection extends RuleDictionnaryDropdownCollection
{
    // From RuleCollection
    //public $rule_class_name = 'RuleDictionnaryManufacturer';

    public string $item_table  = "glpi_manufacturers";
    public string $menu_option = "manufacturers";

    public function getTitle()
    {
        return __('Dictionary of manufacturers');
    }
}
