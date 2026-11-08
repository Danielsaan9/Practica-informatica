<?php

/**
 * Hidden fields for change template class
 *
 * @since 0.83
 **/
class ChangeTemplateHiddenField extends ITILTemplateHiddenField
{
    // From CommonDBChild
    public static string $itemtype = ChangeTemplate::class;
    public static string $items_id  = 'changetemplates_id';
    public static $itiltype = Change::class;
}
