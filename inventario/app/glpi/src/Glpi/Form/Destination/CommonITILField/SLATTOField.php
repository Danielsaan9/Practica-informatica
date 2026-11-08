<?php

namespace Glpi\Form\Destination\CommonITILField;

use LevelAgreement;
use Override;
use SLA;
use SLM;

final class SLATTOField extends SLAField
{
    #[Override]
    public function getLabel(): string
    {
        return __("TTO");
    }

    #[Override]
    public function getWeight(): int
    {
        return 200;
    }

    #[Override]
    public function getSLM(): LevelAgreement
    {
        return new SLA();
    }

    #[Override]
    public function getType(): int
    {
        return SLM::TTO;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return SLATTOFieldConfig::class;
    }

    #[Override]
    protected function getFieldNameToConvertSpecificSLMID(): string
    {
        return 'sla_question_tto';
    }
}
