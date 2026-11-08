<?php

/// Readonly fields for ticket template class
/// since version 11.0.0
class TicketTemplateReadonlyField extends ITILTemplateReadonlyField
{
    // From CommonDBChild
    public static string $itemtype = TicketTemplate::class;
    public static string $items_id  = 'tickettemplates_id';
    public static $itiltype = Ticket::class;
}
