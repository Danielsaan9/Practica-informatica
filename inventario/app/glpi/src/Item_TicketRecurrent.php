<?php

/**
 * Item_TicketRecurrent Class
 *
 *  Relation between TicketRecurrents and Items
 **/
class Item_TicketRecurrent extends CommonItilObject_Item
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = TicketRecurrent::class;
    public static ?string $items_id_1          = 'ticketrecurrents_id';

    public static ?string $itemtype_2          = 'itemtype';
    public static ?string $items_id_2          = 'items_id';
    public static int $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;

    public static function getTypeName($nb = 0)
    {
        return _n('Ticket recurrent item', 'Ticket recurrent items', $nb);
    }

    public static function itemAddForm(CommonITILObject|CommonITILRecurrent $ticketrecurrent, $options = [])
    {
        parent::displayItemAddForm($ticketrecurrent, $options);
    }
}
