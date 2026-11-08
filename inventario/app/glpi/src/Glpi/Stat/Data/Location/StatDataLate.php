<?php

namespace Glpi\Stat\Data\Location;

use Session;
use Ticket;

class StatDataLate extends StatDataLocation
{
    public function getKey(): string
    {
        return "late";
    }

    public function getTitle(): string
    {
        return sprintf(
            __('Late solved %1$s (%2$s)'),
            Ticket::getTypeName(Session::getPluralNumber()),
            $this->getTotal()
        );
    }
}
