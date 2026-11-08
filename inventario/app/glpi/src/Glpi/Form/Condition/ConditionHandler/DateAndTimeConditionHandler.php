<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\Form\Condition\ConditionData;
use Override;

final class DateAndTimeConditionHandler extends AbstractDateTimeConditionHandler
{
    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return ['attributes' => ['type' => 'datetime-local']];
    }
}
