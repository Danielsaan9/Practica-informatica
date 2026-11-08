<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\Form\Condition\ConditionData;
use Glpi\Urgency;
use Override;

/**
 * Since an urgency is represented by a number, we can reuse the
 * NumberConditionHandler here with a different input template to properly
 * display the urgency dropdown.
 */
final class UrgencyConditionHandler extends NumberConditionHandler
{
    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/dropdown.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return ['values' => Urgency::getEnabledUrgencyValuesForDropdown()];
    }
}
