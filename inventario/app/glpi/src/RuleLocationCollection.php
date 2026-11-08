<?php

class RuleLocationCollection extends RuleCollection
{
    public bool $stop_on_first_match = true;
    public static string $rightname    = 'rule_location';
    public string $menu_option         = 'location';

    public function getTitle()
    {
        return __("Location rules");
    }
}
