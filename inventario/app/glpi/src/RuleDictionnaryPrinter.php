<?php

/**
 * Rule class store all information about a GLPI rule :
 *   - description
 *   - criterias
 *   - actions
 **/
class RuleDictionnaryPrinter extends Rule
{
    public static string $rightname = 'rule_dictionnary_printer';


    public function getTitle()
    {
        return __('Dictionary of printers');
    }

    public function getCriterias()
    {
        static $criterias = [];

        if (count($criterias)) {
            return $criterias;
        }

        $criterias['name']['field']         = 'name';
        $criterias['name']['name']          = __('Name');
        $criterias['name']['table']         = 'glpi_printers';

        $criterias['manufacturer']['field'] = 'name';
        $criterias['manufacturer']['name']  = Manufacturer::getTypeName(1);
        $criterias['manufacturer']['table'] = '';

        $criterias['comment']['field']      = 'comment';
        $criterias['comment']['name']       = _n('Comment', 'Comments', Session::getPluralNumber());
        $criterias['comment']['table']      = '';

        return $criterias;
    }

    public function getActions()
    {
        $actions                               = parent::getActions();

        $actions['name']['name']               = __('Name');
        $actions['name']['force_actions']      = ['assign', 'regex_result'];

        $actions['_ignore_import']['name']     = __('To be unaware of import');
        $actions['_ignore_import']['type']     = 'yesonly';

        $actions['manufacturer']['name']       = Manufacturer::getTypeName(1);
        $actions['manufacturer']['table']      = 'glpi_manufacturers';
        $actions['manufacturer']['type']       = 'dropdown';

        $actions['is_global']['name']          = __('Management type');
        $actions['is_global']['type']          = 'dropdown_management';

        return $actions;
    }
}
