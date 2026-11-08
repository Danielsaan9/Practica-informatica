<?php

namespace Glpi\Stat\Data\Location;

use Session;
use Ticket;

class StatDataSolved extends StatDataLocation
{
    public function getKey(): string
    {
        return "solved";
    }

    public function getTitle(): string
    {
        return sprintf(
            __('Solved %1$s (%2$s)'),
            Ticket::getTypeName(Session::getPluralNumber()),
            $this->getTotal()
        );
    }
}
