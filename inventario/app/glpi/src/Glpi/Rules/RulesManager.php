<?php

namespace Glpi\Rules;

use Config;
use Rule;
use RuleCollection;

use function Safe\json_decode;
use function Safe\json_encode;

final class RulesManager
{
    /**
     * Initialize rules for each collection that does not yet contains any rule.
     */
    public static function initializeRules(): bool
    {
        global $CFG_GLPI;

        $rulecollections_types = $CFG_GLPI['rulecollections_types'];
        $has_initialized_rules = false;

        foreach ($CFG_GLPI['dictionnary_types'] as $itemtype) {
            $rulecollection = RuleCollection::getClassByType($itemtype);
            if ($rulecollection instanceof RuleCollection) {
                $rulecollections_types[] = get_class($rulecollection);
            }
        }

        $initialized_collections = json_decode(
            Config::getConfigurationValue('core', 'initialized_rules_collections'),
            true
        );
        if (!is_array($initialized_collections)) {
            // Reinitialize configuration value if stored value does not exists or is corrupted.
            // It can happen either if migration did not worked as expected, either if value
            // in database was corrupted/deleted.
            $initialized_collections = [];
        }

        foreach ($rulecollections_types as $rulecollection_type) {
            if (
                !is_a($rulecollection_type, RuleCollection::class, true)
                || in_array($rulecollection_type, $initialized_collections)
            ) {
                continue;
            }

            $rulecollection = new $rulecollection_type();
            $ruleclass = $rulecollection->getRuleClass();
            if (!($ruleclass instanceof Rule) || !$ruleclass->hasDefaultRules()) {
                continue;
            }

            if (countElementsInTable(Rule::getTable(), ['sub_type' => $ruleclass::class]) === 0) {
                $ruleclass->initRules(false);
                $has_initialized_rules = true;
            }

            // Mark collection as already initialized, to not reinitialize it on next update
            // if admin remove all corresponding rules.
            $initialized_collections[] = get_class($rulecollection);
            Config::setConfigurationValues(
                'core',
                ['initialized_rules_collections' => json_encode($initialized_collections)]
            );
        }

        return $has_initialized_rules;
    }
}
