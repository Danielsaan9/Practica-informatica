<?php

/**
 * Group_Ticket Class
 *
 * @since 0.85
 *
 * Relation between Groups and Tickets
 **/
class Group_Ticket extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Ticket::class;
    public static ?string $items_id_1 = 'tickets_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2 = 'groups_id';

    #[Override]
    public function post_purgeItem()
    {
        Item_Ola::computeGroupAssigneeRemoval((int) $this->fields['tickets_id'], (int) $this->fields['groups_id']);

        parent::post_purgeItem();
    }
}
