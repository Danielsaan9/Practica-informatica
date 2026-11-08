<?php

class RuleProblemCollection extends RuleCommonITILObjectCollection
{
    // From RuleCollection
    public static string $rightname                        = 'rule_problem';
    public string $menu_option                             = 'problem';

    public function getTitle()
    {
        return __('Business rules for problems');
    }
}
