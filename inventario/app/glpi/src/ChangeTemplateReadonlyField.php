<?php

/**
 * Readonly fields for change template class
 *
 * @since 11.0.0
 */
class ChangeTemplateReadonlyField extends ITILTemplateReadonlyField
{
    // From CommonDBChild
    public static string $itemtype = ChangeTemplate::class;
    public static string $items_id  = 'changetemplates_id';
    public static $itiltype = Change::class;
}
