<?php

/**
 * TicketTemplatePredefinedField Class
 *
 * Predefined fields for ticket template class
 *
 * @since 0.83
 **/
class TicketTemplatePredefinedField extends ITILTemplatePredefinedField
{
    // From CommonDBChild
    public static string $itemtype = TicketTemplate::class;
    public static string $items_id = 'tickettemplates_id';
    public static $itiltype = Ticket::class;
}
