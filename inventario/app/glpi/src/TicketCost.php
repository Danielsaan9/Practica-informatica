<?php

/**
 * TicketCost Class
 *
 * @since 0.84
 **/
class TicketCost extends CommonITILCost
{
    // From CommonDBChild
    public static string $itemtype = Ticket::class;
    public static string $items_id  = 'tickets_id';

    public static string $rightname        = 'ticketcost';

    public function post_updateItem($history = true)
    {
        parent::post_updateItem($history);

        $this->verifTCOItem();
    }

    public function post_addItem()
    {
        parent::post_addItem();

        $this->verifTCOItem();
    }

    public function post_purgeItem()
    {
        parent::post_purgeItem();

        $this->verifTCOItem();
    }

    private function verifTCOItem(): void
    {
        if ($this->fields['tickets_id']) {
            $item_ticket = new Item_Ticket();
            $item_tickets = $item_ticket->find([
                'tickets_id' => $this->fields['tickets_id'],
            ]);
            foreach ($item_tickets as $it) {
                $this->updateTCOItem($it['itemtype'], $it['items_id']);
            }
        }
    }

    public function updateTCOItem(string $itemtype, int $items_id): void
    {
        $item = getItemForItemtype($itemtype);
        if ($item && $item->isField('ticket_tco') && $item->getFromDB($items_id)) {
            $item->update([
                'id' => $items_id,
                'ticket_tco' => Ticket::computeTco($item),
            ]);
        }
    }
}
