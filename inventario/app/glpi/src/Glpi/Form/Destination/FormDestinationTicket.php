<?php

namespace Glpi\Form\Destination;

use CommonITILObject;
use Glpi\Form\Destination\CommonITILField\OLATTOField;
use Glpi\Form\Destination\CommonITILField\OLATTRField;
use Glpi\Form\Destination\CommonITILField\RequestTypeField;
use Glpi\Form\Destination\CommonITILField\SLATTOField;
use Glpi\Form\Destination\CommonITILField\SLATTRField;
use Glpi\Form\Destination\CommonITILField\StatusField;
use Override;
use Ticket;

final class FormDestinationTicket extends AbstractCommonITILFormDestination
{
    #[Override]
    public function getTarget(): CommonITILObject
    {
        return new Ticket();
    }

    #[Override]
    protected function defineConfigurableFields(): array
    {
        return array_merge(parent::defineConfigurableFields(), [
            new RequestTypeField(),
            new SLATTOField(),
            new SLATTRField(),
            new OLATTOField(),
            new OLATTRField(),
            new StatusField(),
        ]);
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }
}
