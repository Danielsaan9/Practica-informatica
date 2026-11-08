<?php

namespace Glpi\Form\Destination\CommonITILField;

use LevelAgreement;
use OLA;
use Override;
use SLM;

final class OLATTOField extends OLAField
{
    #[Override]
    public function getLabel(): string
    {
        return __("Internal TTO");
    }

    #[Override]
    public function getWeight(): int
    {
        return 220;
    }

    #[Override]
    public function getSLM(): LevelAgreement
    {
        return new OLA();
    }

    #[Override]
    public function getType(): int
    {
        return SLM::TTO;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return OLATTOFieldConfig::class;
    }

    #[Override]
    protected function getFieldNameToConvertSpecificSLMID(): string
    {
        return 'ola_question_tto';
    }
}
