<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\Form\Condition\ConditionData;
use Override;

final class TimeConditionHandler extends AbstractDateTimeConditionHandler
{
    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return ['attributes' => ['type' => 'time']];
    }
}
