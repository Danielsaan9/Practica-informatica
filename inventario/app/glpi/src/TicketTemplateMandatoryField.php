<?php

/// Mandatory fields for ticket template class
/// since version 0.83
class TicketTemplateMandatoryField extends ITILTemplateMandatoryField
{
    // From CommonDBChild
    public static string $itemtype = TicketTemplate::class;
    public static string $items_id  = 'tickettemplates_id';
    public static $itiltype = Ticket::class;

    public static function getExcludedFields()
    {
        return [
            14 => 14, // ticket type has no empty option
        ] + parent::getExcludedFields();
    }
}
