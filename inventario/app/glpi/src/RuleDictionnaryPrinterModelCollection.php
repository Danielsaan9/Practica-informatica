<?php

class RuleDictionnaryPrinterModelCollection extends RuleDictionnaryDropdownCollection
{
    public string $item_table  = "glpi_printermodels";
    public string $menu_option = "model.printer";

    public function getTitle()
    {
        return __('Dictionary of printer models');
    }
}
