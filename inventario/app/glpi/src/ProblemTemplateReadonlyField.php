<?php

/// Readonly fields for problem template class
/// since version 11.0.0
class ProblemTemplateReadonlyField extends ITILTemplateReadonlyField
{
    // From CommonDBChild
    public static string $itemtype = ProblemTemplate::class;
    public static string $items_id  = 'problemtemplates_id';
    public static $itiltype = Problem::class;
}
