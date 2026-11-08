<?php

class RuleChangeCollection extends RuleCommonITILObjectCollection
{
    // From RuleCollection
    public static string $rightname                        = 'rule_change';
    public string $menu_option                             = 'change';

    public function getTitle()
    {
        return __('Business rules for changes');
    }
}
