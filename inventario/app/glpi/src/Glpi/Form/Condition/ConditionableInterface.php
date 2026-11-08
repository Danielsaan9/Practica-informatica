<?php

namespace Glpi\Form\Condition;

/**
 * This interface must be satisfied by any form item for which its visibility
 * can be toggled depending on some conditions.
 */
interface ConditionableInterface
{
    /**
     * Get configured condition data from the database.
     *
     *  @return ConditionData[]
     **/
    public function getConfiguredConditionsData(): array;
}
