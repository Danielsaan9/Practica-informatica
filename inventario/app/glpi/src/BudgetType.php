<?php

/**
 * Class BudgetType
 * @since 9.1
 **/
class BudgetType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Budget type', 'Budget types', $nb);
    }
}
