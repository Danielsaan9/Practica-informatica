<?php

namespace Glpi\Stat\Data\Location;

use Session;
use Ticket;

class StatDataOpenSatisfaction extends StatDataLocation
{
    public function getKey(): string
    {
        return "opensatisfaction";
    }

    public function getTitle(): string
    {
        return sprintf(
            __('%1$s satisfaction survey (%2$s)'),
            Ticket::getTypeName(Session::getPluralNumber()),
            $this->getTotal()
        );
    }
}
