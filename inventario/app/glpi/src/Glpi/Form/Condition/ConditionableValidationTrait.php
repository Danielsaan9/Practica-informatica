<?php

namespace Glpi\Form\Condition;

use function Safe\json_encode;

trait ConditionableValidationTrait
{
    use ConditionableTrait {
        getConditionsFieldName as getValidationConditionsFieldName;
        getConfiguredConditionsData as getConfiguredValidationConditionsData;
    }

    /**
     * Get the field name used for visibility strategy
     * Classes using this trait can override this method to customize the field name
     *
     * @return string
     */
    protected function getValidationStrategyFieldName(): string
    {
        return 'validation_strategy';
    }

    /** @return ConditionData[] */
    public function getConfiguredValidationConditionsData(): array
    {
        return $this->getConditionsData($this->getValidationConditionsFieldName());
    }

    /**
     * Override the getConditionsFieldName method from ConditionableTrait
     * to return the validation conditions field name
     *
     * @return string
     */
    protected function getValidationConditionsFieldName(): string
    {
        return 'validation_conditions';
    }

    public function getConfiguredValidationStrategy(): ValidationStrategy
    {
        $field_name = $this->getValidationStrategyFieldName();
        $strategy_value = $this->fields[$field_name] ?? "";
        $strategy = ValidationStrategy::tryFrom($strategy_value);
        return $strategy ?? ValidationStrategy::NO_VALIDATION;
    }

    protected function removeSavedConditionsIfNoValidation(array $input): array
    {
        $strategy_field = $this->getValidationStrategyFieldName();
        $condition_field = $this->getValidationConditionsFieldName();

        if (
            isset($input[$strategy_field])
            && $input[$strategy_field] == ValidationStrategy::NO_VALIDATION->value
        ) {
            $input[$condition_field] = json_encode([]);
        }

        return $input;
    }
}
