<?php

class RuleAssetCollection extends RuleCollection
{
    // From RuleCollection
    public bool $stop_on_first_match = false;
    public static string $rightname           = 'rule_asset';
    public string $menu_option         = 'ruleasset';

    public function getTitle()
    {
        return __('Business rules for assets');
    }

    public function cleanTestOutputCriterias(array $output)
    {
        if (isset($output["_rule_process"])) {
            unset($output["_rule_process"]);
        }
        return $output;
    }
}
