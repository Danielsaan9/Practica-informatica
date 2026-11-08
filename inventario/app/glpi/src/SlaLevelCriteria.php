<?php

/**
 * @since 0.84
 */


/// Class SlaLevelCriteria
class SlaLevelCriteria extends RuleCriteria
{
    public static string $itemtype = SlaLevel::class;
    public static string $items_id  = 'slalevels_id';
    public bool $dohistory        = true;


    public function __construct($rule_type = 'SlaLevel')
    {
        // Override in order not to use glpi_rules table.
        if ($rule_type !== static::$itemtype) {
            throw new LogicException(
                sprintf(
                    '%s is not expected to be used with a different rule type than %s',
                    static::class,
                    static::$itemtype
                )
            );
        }
    }

    public function rawSearchOptions()
    {
        // RuleCriteria search options requires value of rules_id field which does not exists here
        return [];
    }
}
