<?php

/// Class Ticket_User
class Ticket_User extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Ticket::class;
    public static ?string $items_id_1 = 'tickets_id';
    public static ?string $itemtype_2 = User::class;
    public static ?string $items_id_2 = 'users_id';
}
