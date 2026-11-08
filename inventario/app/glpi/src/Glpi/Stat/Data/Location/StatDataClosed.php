<?php

namespace Glpi\Stat\Data\Location;

use Session;
use Ticket;

class StatDataClosed extends StatDataLocation
{
    public function getKey(): string
    {
        return "closed";
    }

    public function getTitle(): string
    {
        return sprintf(
            __('Closed %1$s (%2$s)'),
            Ticket::getTypeName(Session::getPluralNumber()),
            $this->getTotal()
        );
    }
}
