<?php

/// Class Ticket links
class Ticket_Ticket extends CommonITILObject_CommonITILObject
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Ticket::class;
    public static ?string $items_id_1     = 'tickets_id_1';
    public static ?string $itemtype_2 = Ticket::class;
    public static ?string $items_id_2     = 'tickets_id_2';

    public static bool $check_entity_coherency = false;

    public static function getTypeName($nb = 0)
    {
        return _n('Linked ticket', 'Linked tickets', $nb);
    }
}
