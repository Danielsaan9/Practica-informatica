<?php

namespace Glpi\Form\Destination\CommonITILField;

use LevelAgreement;
use OLA;
use Override;
use SLM;

final class OLATTRField extends OLAField
{
    #[Override]
    public function getLabel(): string
    {
        return __("Internal TTR");
    }

    #[Override]
    public function getWeight(): int
    {
        return 230;
    }

    #[Override]
    public function getSLM(): LevelAgreement
    {
        return new OLA();
    }

    #[Override]
    public function getType(): int
    {
        return SLM::TTR;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return OLATTRFieldConfig::class;
    }

    #[Override]
    protected function getFieldNameToConvertSpecificSLMID(): string
    {
        return 'ola_question_ttr';
    }
}
