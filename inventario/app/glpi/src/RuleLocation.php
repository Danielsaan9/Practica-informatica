<?php

class RuleLocation extends Rule
{
    public static string $rightname = 'rule_location';

    public function getTitle()
    {
        return __('Location rules');
    }

    public function executeActions($output, $params, array $input = [])
    {
        foreach ($this->actions as $action) {
            switch ($action->fields["action_type"]) {
                case "assign":
                    $output[$action->fields["field"]] = $action->fields["value"];
                    break;
                case 'regex_result':
                    if ($action->fields["field"] === "locations_id") {
                        foreach ($this->regex_results as $regex_result) {
                            $regexvalue          = RuleAction::getRegexResultById(
                                $action->fields["value"],
                                $regex_result
                            );

                            // from rule test context just assign regex value to key
                            if ($this->is_preview) {
                                $output['locations_id'] = $regexvalue;
                            } else {
                                $compute_entities_id = $input['entities_id'] ?? 0;
                                $location = new Location();
                                $output['locations_id'] = $location->importExternal($regexvalue, $compute_entities_id);
                            }
                        }
                    }
                    break;
            }
        }
        return $output;
    }

    public function getCriterias()
    {
        return [
            'itemtype' => [
                'name'            => sprintf('%s > %s', _n('Asset', 'Assets', 1), __('Item type')),
                'type'            => 'dropdown_inventory_itemtype',
                'is_global'       => false,
                'allow_condition' => [
                    Rule::PATTERN_IS,
                    Rule::PATTERN_IS_NOT,
                    Rule::PATTERN_EXISTS,
                    Rule::PATTERN_DOES_NOT_EXISTS,
                ],
            ],
            'tag' => [
                'name'            => sprintf('%s > %s', Agent::getTypeName(1), __('Inventory tag')),
            ],
            'domain' => [
                'name'            => Domain::getTypeName(1),
            ],
            'subnet' => [
                'name'            => __("Subnet"),
            ],
            'ip' => [
                'name'            => sprintf('%s > %s', NetworkPort::getTypename(1), __('IP')),
            ],
            'name' => [
                'name'            => __("Name"),
            ],
            'serial' => [
                'name'            => __("Serial number"),
            ],
            'oscomment' => [
                'name'            => sprintf('%s > %s', OperatingSystem::getTypeName(1), _n('Comment', 'Comments', Session::getPluralNumber())),
            ],
        ];
    }

    public function getActions()
    {
        return [
            'locations_id' => [
                'name'  => _n('Location', 'Locations', 1),
                'type'  => 'dropdown',
                'table' => Location::getTable(),
                'force_actions' => [
                    'assign',
                    'regex_result',
                ],
            ],
        ];
    }

    public static function getIcon()
    {
        return Location::getIcon();
    }
}
