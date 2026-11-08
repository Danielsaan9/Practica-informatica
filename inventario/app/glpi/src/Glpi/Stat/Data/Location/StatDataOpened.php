<?php

namespace Glpi\Stat\Data\Location;

use Session;
use Ticket;

class StatDataOpened extends StatDataLocation
{
    public function getKey(): string
    {
        return "opened";
    }

    public function getTitle(): string
    {
        return sprintf(
            __('Opened %1$s (%2$s)'),
            Ticket::getTypeName(Session::getPluralNumber()),
            $this->getTotal()
        );
    }
}
