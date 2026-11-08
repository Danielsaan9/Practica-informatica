<?php

/// Hidden fields for ticket template class
/// since version 0.83
class TicketTemplateHiddenField extends ITILTemplateHiddenField
{
    // From CommonDBChild
    public static string $itemtype = TicketTemplate::class;
    public static string $items_id  = 'tickettemplates_id';
    public static $itiltype = Ticket::class;
}
