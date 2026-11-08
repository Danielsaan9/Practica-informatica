<?php

/// Import rules collection class
class RuleImportEntityCollection extends RuleCollection
{
    // From RuleCollection
    public bool $stop_on_first_match = true;
    public static string $rightname           = 'rule_import';
    public string $menu_option         = 'importentity';


    public function canList()
    {
        return static::canView();
    }

    public function getTitle()
    {
        return __('Rules for assigning an item to an entity');
    }
}
