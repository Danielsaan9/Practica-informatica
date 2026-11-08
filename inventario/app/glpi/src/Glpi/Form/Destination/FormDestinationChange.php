<?php

namespace Glpi\Form\Destination;

use Change;
use CommonITILObject;
use Glpi\Form\Destination\CommonITILField\BackupPlanField;
use Glpi\Form\Destination\CommonITILField\CheckListField;
use Glpi\Form\Destination\CommonITILField\ControlsListField;
use Glpi\Form\Destination\CommonITILField\DeploymentPlanField;
use Glpi\Form\Destination\CommonITILField\ImpactsField;
use Glpi\Form\Destination\CommonITILField\SLATTRField;
use Override;

final class FormDestinationChange extends AbstractCommonITILFormDestination
{
    #[Override]
    public function getTarget(): CommonITILObject
    {
        return new Change();
    }

    #[Override]
    public function getWeight(): int
    {
        return 20;
    }

    #[Override]
    protected function defineConfigurableFields(): array
    {
        return array_merge(parent::defineConfigurableFields(), [
            new ImpactsField(),
            new ControlsListField(),
            new DeploymentPlanField(),
            new BackupPlanField(),
            new CheckListField(),
            new SLATTRField(support_only_dates: true),
        ]);
    }
}
