<?php

namespace Glpi\Form\Condition;

use function Safe\json_encode;

trait ConditionableCreationTrait
{
    use ConditionableTrait;

    public function getConfiguredCreationStrategy(): CreationStrategy
    {
        $strategy_value = $this->fields['creation_strategy'] ?? "";
        $strategy = CreationStrategy::tryFrom($strategy_value);
        return $strategy ?? CreationStrategy::ALWAYS_CREATED;
    }

    protected function removeSavedConditionsIfAlwaysCreated(array $input): array
    {
        $strategy_field = 'creation_strategy';
        $condition_field = $this->getConditionsFieldName();

        if (
            isset($input[$strategy_field])
            && $input[$strategy_field] == CreationStrategy::ALWAYS_CREATED->value
        ) {
            $input[$condition_field] = json_encode([]);
        }

        return $input;
    }
}
