<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Form;
use Glpi\Form\Migration\FormMigration;
use LevelAgreement;
use Override;
use SLA;
use SLM;

final class SLATTRField extends SLAField
{
    #[Override]
    public function getLabel(): string
    {
        return __("TTR");
    }

    #[Override]
    public function getWeight(): int
    {
        return 210;
    }

    #[Override]
    public function getSLM(): LevelAgreement
    {
        return new SLA();
    }

    #[Override]
    public function getType(): int
    {
        return SLM::TTR;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return SLATTRFieldConfig::class;
    }

    #[Override]
    protected function getFieldNameToConvertSpecificSLMID(): string
    {
        return 'sla_question_ttr';
    }

    #[Override]
    public function convertFieldConfig(FormMigration $migration, Form $form, array $rawData): JsonFieldInterface
    {
        $parent_config = parent::convertFieldConfig($migration, $form, $rawData);
        if ($parent_config != $this->getDefaultConfig($form) || !isset($rawData['due_date_rule'])) {
            return $parent_config;
        }

        switch ($rawData['due_date_rule']) {
            case 2: // PluginFormcreatorAbstractItilTarget::DUE_DATE_RULE_ANSWER
                return $this->getConfig($form, [$this->getKey() => [
                    SLMFieldConfig::STRATEGY => SLMFieldStrategy::SPECIFIC_DATE_ANSWER->value,
                    SLMFieldConfig::QUESTION_ID => $migration->getMappedItemTarget(
                        'PluginFormcreatorQuestion',
                        $rawData['due_date_question'] ?? 0
                    )['items_id'],
                ]]);
            case 3: // PluginFormcreatorAbstractItilTarget::DUE_DATE_RULE_TICKET
                return $this->getConfig($form, [$this->getKey() => [
                    SLMFieldConfig::STRATEGY => SLMFieldStrategy::COMPUTED_DATE_FROM_FORM_SUBMISSION->value,
                    SLMFieldConfig::TIME_OFFSET => (int) ($rawData['due_date_value'] ?? 0),
                    SLMFieldConfig::TIME_DEFINITION => $this->getTimeDefinitionFromLegacy($rawData['due_date_period']),
                ]]);
            case 4: // PluginFormcreatorAbstractItilTarget::DUE_DATE_RULE_CALC
                return $this->getConfig($form, [$this->getKey() => [
                    SLMFieldConfig::STRATEGY => SLMFieldStrategy::COMPUTED_DATE_FROM_SPECIFIC_DATE_ANSWER->value,
                    SLMFieldConfig::QUESTION_ID => $migration->getMappedItemTarget(
                        'PluginFormcreatorQuestion',
                        $rawData['due_date_question'] ?? 0
                    )['items_id'],
                    SLMFieldConfig::TIME_OFFSET => (int) ($rawData['due_date_value'] ?? 0),
                    SLMFieldConfig::TIME_DEFINITION => $this->getTimeDefinitionFromLegacy($rawData['due_date_period']),
                ]]);
        }

        return $this->getDefaultConfig($form);
    }

    private function getTimeDefinitionFromLegacy(int $due_date_value): string
    {
        $time_keys = array_keys(LevelAgreement::getDefinitionTimeValues());
        if (array_key_exists($due_date_value - 1, $time_keys)) {
            return $time_keys[$due_date_value - 1];
        }

        // Fallback to first value
        return current($time_keys);
    }
}
