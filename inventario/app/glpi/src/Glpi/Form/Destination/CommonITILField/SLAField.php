<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\AnswersSet;
use InvalidArgumentException;
use Override;

abstract class SLAField extends SLMField
{
    #[Override]
    public function applyConfiguratedValueToInputUsingAnswers(
        JsonFieldInterface $config,
        array $input,
        AnswersSet $answers_set
    ): array {
        if (!$config instanceof SLMFieldConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        // Only one strategy is allowed
        $strategy = current($config->getStrategies());

        // Compute value according to strategy
        $slm_id = $strategy->getSLMID($config);

        // Do not edit input if invalid value was found
        $slm = $this->getSLM();
        if ($slm::getById($slm_id)) {
            $input[$slm::getFieldNames($this->getType())[1]] = $slm_id;
        }

        // Compute date value according to strategy
        $date_slm = $strategy->getDateSLM($config, $answers_set);
        if ($date_slm !== null) {
            $input[$slm::getFieldNames($this->getType())[0]] = $date_slm;
        }

        return $input;
    }
}
