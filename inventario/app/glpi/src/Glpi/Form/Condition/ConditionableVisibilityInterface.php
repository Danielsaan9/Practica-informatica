<?php

namespace Glpi\Form\Condition;

interface ConditionableVisibilityInterface extends ConditionableInterface
{
    /**
     * Get the configured visibility strategy from the database.
     *
     * @return VisibilityStrategy
     */
    public function getConfiguredVisibilityStrategy(): VisibilityStrategy;

    /**
     * Get the UUID of the item.
     *
     * @return string
     */
    public function getUUID(): string;

    // TODO: uncomment on main to prevent BC breaks
    // protected function removeSavedConditionsIfAlwaysVisible(array $input): array;
}
