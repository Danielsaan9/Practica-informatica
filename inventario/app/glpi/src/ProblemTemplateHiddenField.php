<?php

/// Hidden fields for problem template class
/// since version 0.83
class ProblemTemplateHiddenField extends ITILTemplateHiddenField
{
    // From CommonDBChild
    public static string $itemtype = ProblemTemplate::class;
    public static string $items_id  = 'problemtemplates_id';
    public static $itiltype = Problem::class;
}
