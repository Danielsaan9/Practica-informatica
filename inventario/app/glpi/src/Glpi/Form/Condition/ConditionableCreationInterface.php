<?php

namespace Glpi\Form\Condition;

interface ConditionableCreationInterface extends ConditionableInterface
{
    public function getConfiguredCreationStrategy(): CreationStrategy;

    // TODO: uncomment on main to prevent BC breaks
    // FIXME: An interface cannot declare a protected function
    // protected function removeSavedConditionsIfAlwaysCreated(array $input): array;
}
