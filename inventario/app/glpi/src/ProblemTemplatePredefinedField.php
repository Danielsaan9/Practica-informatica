<?php

/**
 * ProblemTemplatePredefinedField Class
 *
 * Predefined fields for problem template class
 *
 * @since 0.83
 **/
class ProblemTemplatePredefinedField extends ITILTemplatePredefinedField
{
    // From CommonDBChild
    public static string $itemtype = ProblemTemplate::class;
    public static string $items_id = 'problemtemplates_id';
    public static $itiltype = Problem::class;
}
