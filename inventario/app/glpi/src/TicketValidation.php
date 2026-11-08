<?php

/**
 * TicketValidation class
 */
class TicketValidation extends CommonITILValidation
{
    // From CommonDBChild
    public static string $itemtype = Ticket::class;
    public static string $items_id           = 'tickets_id';

    public static string $rightname                 = 'ticketvalidation';

    public const CREATEREQUEST               = 1024;
    public const CREATEINCIDENT              = 2048;
    public const VALIDATEREQUEST             = 4096;
    public const VALIDATEINCIDENT            = 8192;



    public static function getCreateRights()
    {
        return [static::CREATEREQUEST, static::CREATEINCIDENT];
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Ticket approval', 'Ticket approvals', $nb);
    }

    public static function getValidateRights()
    {
        return [static::VALIDATEREQUEST, static::VALIDATEINCIDENT];
    }


    /**
     * @since 0.85
     **/
    public function canCreateItem(): bool
    {

        if ($this->canChildItem('canViewItem', 'canView')) {
            $parent_loaded = $this->isParentAlreadyLoaded();
            $ticket = $parent_loaded ? $this->item : new Ticket();
            if ($parent_loaded || $ticket->getFromDB($this->fields['tickets_id'])) {
                // No validation for closed tickets
                if (in_array($ticket->fields['status'], $ticket->getClosedStatusArray())) {
                    return false;
                }

                if ($ticket->fields['type'] == Ticket::INCIDENT_TYPE) {
                    return Session::haveRight(self::$rightname, self::CREATEINCIDENT);
                }
                if ($ticket->fields['type'] == Ticket::DEMAND_TYPE) {
                    return Session::haveRight(self::$rightname, self::CREATEREQUEST);
                }
            }
        }

        return parent::canCreateItem();
    }

    /**
     * @since 0.85
     *
     * @see commonDBTM::getRights()
     **/
    public function getRights($interface = 'central')
    {

        $values = parent::getRights();
        unset($values[UPDATE], $values[CREATE], $values[READ]);

        $values[self::CREATEREQUEST]
                              = ['short' => __('Create for request'),
                                  'long'  => __('Create an approval request for a request'),
                              ];
        $values[self::CREATEINCIDENT]
                              = ['short' => __('Create for incident'),
                                  'long'  => __('Create an approval request for an incident'),
                              ];
        $values[self::VALIDATEREQUEST]
                              = __('Approve a request');
        $values[self::VALIDATEINCIDENT]
                              = __('Approve an incident');

        if ($interface == 'helpdesk') {
            unset($values[PURGE]);
        }

        return $values;
    }
}
