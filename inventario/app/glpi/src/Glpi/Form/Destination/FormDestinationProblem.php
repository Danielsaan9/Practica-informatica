<?php

namespace Glpi\Form\Destination;

use CommonITILObject;
use Glpi\Form\Destination\CommonITILField\CausesField;
use Glpi\Form\Destination\CommonITILField\ImpactsField;
use Glpi\Form\Destination\CommonITILField\SLATTRField;
use Glpi\Form\Destination\CommonITILField\SymptomsField;
use Override;
use Problem;

final class FormDestinationProblem extends AbstractCommonITILFormDestination
{
    #[Override]
    public function getTarget(): CommonITILObject
    {
        return new Problem();
    }

    #[Override]
    public function getWeight(): int
    {
        return 30;
    }

    #[Override]
    protected function defineConfigurableFields(): array
    {
        return array_merge(parent::defineConfigurableFields(), [
            new ImpactsField(),
            new CausesField(),
            new SymptomsField(),
            new SLATTRField(support_only_dates: true),
        ]);
    }
}
