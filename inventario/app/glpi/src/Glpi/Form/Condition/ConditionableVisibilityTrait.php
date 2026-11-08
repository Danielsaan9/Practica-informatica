<?php

namespace Glpi\Form\Condition;

use function Safe\json_encode;

trait ConditionableVisibilityTrait
{
    use ConditionableTrait;

    /**
     * Get the field name used for visibility strategy
     * Classes using this trait can override this method to customize the field name
     *
     * @return string
     */
    protected function getVisibilityStrategyFieldName(): string
    {
        return 'visibility_strategy';
    }

    public function getConfiguredVisibilityStrategy(): VisibilityStrategy
    {
        $field_name = $this->getVisibilityStrategyFieldName();
        $strategy_value = $this->fields[$field_name] ?? "";
        $strategy = VisibilityStrategy::tryFrom($strategy_value);
        return $strategy ?? VisibilityStrategy::ALWAYS_VISIBLE;
    }

    protected function removeSavedConditionsIfAlwaysVisible(array $input): array
    {
        $visibility_field = $this->getVisibilityStrategyFieldName();
        $condition_field = $this->getConditionsFieldName();

        if (
            isset($input[$visibility_field])
            && $input[$visibility_field] == VisibilityStrategy::ALWAYS_VISIBLE->value
        ) {
            $input[$condition_field] = json_encode([]);
        }

        return $input;
    }
}
