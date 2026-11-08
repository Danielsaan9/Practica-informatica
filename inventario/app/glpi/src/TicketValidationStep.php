<?php

final class TicketValidationStep extends ITIL_ValidationStep
{
    public static string $rightname = 'ticketvalidation';
    public static string $validation_classname = TicketValidation::class;

    public static function getTypeName($nb = 0)
    {
        return _n('Ticket approval step', 'Ticket approval steps', $nb);
    }
}
