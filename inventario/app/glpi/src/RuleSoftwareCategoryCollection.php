<?php

class RuleSoftwareCategoryCollection extends RuleCollection
{
    // From RuleCollection
    public bool $stop_on_first_match = true;
    public static string $rightname   = 'rule_softwarecategories';
    public string $menu_option = 'softwarecategories';


    public function getTitle()
    {
        return __('Rules for assigning a category to software');
    }

    public function prepareInputDataForProcess($input, $software)
    {
        $params["name"] = $software["name"];
        if (isset($software["comment"])) {
            $params["comment"] = $software["comment"];
        }
        if (isset($software["_system_category"])) {
            $params["_system_category"] = $software["_system_category"];
        }

        if (isset($software["manufacturers_id"])) {
            $params["manufacturer"] = Dropdown::getDropdownName(
                "glpi_manufacturers",
                $software["manufacturers_id"]
            );
        }
        return $params;
    }
}
