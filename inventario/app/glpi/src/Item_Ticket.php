<?php

/**
 * Item_Ticket Class
 *
 *  Relation between Tickets and Items
 **/
class Item_Ticket extends CommonItilObject_Item
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Ticket::class;
    public static ?string $items_id_1          = 'tickets_id';

    public static ?string $itemtype_2          = 'itemtype';
    public static ?string $items_id_2          = 'items_id';
    public static int $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;

    public static function getTypeName($nb = 0)
    {
        return _n('Ticket item', 'Ticket items', $nb);
    }

    public function canCreateItem(): bool
    {
        // Not item linked for closed tickets
        $ticket = new Ticket();
        if (
            $ticket->getFromDB($this->fields['tickets_id'])
            && in_array($ticket->fields['status'], $ticket::getClosedStatusArray())
        ) {
            return false;
        }

        return parent::canCreateItem();
    }
}
