<?php

namespace Glpi\Features;

interface StateInterface
{
    /**
     * Get the visibility of the state field for the item
     *
     * @param int $id State ID
     *
     * @return bool
     */
    public function isStateVisible(int $id): bool;

    /**
     * Get the visibility criteria of the state field to use a filter condition
     *
     * @return array
     */
    public function getStateVisibilityCriteria(): array;
}
