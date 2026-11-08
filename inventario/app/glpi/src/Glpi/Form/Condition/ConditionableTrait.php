<?php

namespace Glpi\Form\Condition;

use JsonException;

trait ConditionableTrait
{
    /**
     * Get the field name used for conditions
     * Can be overridden in the class using this trait
     *
     * @return string
     */
    protected function getConditionsFieldName(): string
    {
        return 'conditions';
    }

    /** @return ConditionData[] */
    public function getConfiguredConditionsData(): array
    {
        return $this->getConditionsData($this->getConditionsFieldName());
    }

    /** @return ConditionData[] */
    private function getConditionsData(string $field_name): array
    {
        parent::post_getFromDB();

        try {
            $raw_data = json_decode(
                json       : $this->fields[$field_name] ?? '{}',
                associative: true,
                flags      : JSON_THROW_ON_ERROR,
            );
        } catch (JsonException $e) {
            $raw_data = [];
        }

        $form_data = new FormData([
            'conditions' => $raw_data,
        ]);

        // Filter out invalid conditions
        $conditions = array_filter(
            $form_data->getConditionsData(),
            fn(ConditionData $condition) => $condition->isValid()
        );

        return $conditions;
    }
}
