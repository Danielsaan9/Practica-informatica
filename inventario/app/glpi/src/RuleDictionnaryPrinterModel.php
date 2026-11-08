<?php

class RuleDictionnaryPrinterModel extends RuleDictionnaryDropdown
{
    public function getCriterias()
    {
        static $criterias = [];

        if (count($criterias)) {
            return $criterias;
        }

        $criterias['name']['field']         = 'name';
        $criterias['name']['name']          = _n('Model', 'Models', 1);
        $criterias['name']['table']         = 'glpi_printermodels';

        $criterias['manufacturer']['field'] = 'name';
        $criterias['manufacturer']['name']  = Manufacturer::getTypeName(1);
        $criterias['manufacturer']['table'] = 'glpi_manufacturers';

        return $criterias;
    }

    public function getActions()
    {
        $actions                          = [];
        $actions['name']['name']          = _n('Model', 'Models', 1);
        $actions['name']['force_actions'] = ['assign', 'regex_result', 'append_regex_result'];
        return $actions;
    }
}
