<?php

namespace Glpi\Features;

use DropdownVisibility;
use Glpi\DBAL\QueryExpression;

trait State
{
    /**
     * Check if itemtype class is present in configuration array
     *
     * @return bool
     */
    private function hasStates(): bool
    {
        global $CFG_GLPI;

        return in_array(static::class, $CFG_GLPI['state_types']);
    }

    /**
     * @see StateInterface::isStateVisible()
     */
    public function isStateVisible(int $id): bool
    {
        if (!$this->hasStates()) {
            return false;
        }

        $dropdownVisibility = new DropdownVisibility();
        return $dropdownVisibility->getFromDBByCrit([
            'itemtype' => \State::class,
            'items_id' => $id,
            'visible_itemtype' => static::class,
            'is_visible' => 1,
        ]);
    }

    /**
     * @see StateInterface::getStateVisibilityCriteria()
     */
    public function getStateVisibilityCriteria(): array
    {
        if (!$this->hasStates()) {
            return [
                'WHERE' => [new QueryExpression('false')],
            ];
        }

        return [
            'LEFT JOIN' => [
                DropdownVisibility::getTable() => [
                    'ON' => [
                        DropdownVisibility::getTable() => 'items_id',
                        \State::getTable() => 'id', [
                            'AND' => [
                                DropdownVisibility::getTable() . '.itemtype' => \State::class,
                            ],
                        ],
                    ],
                ],
            ],
            'WHERE' => [
                DropdownVisibility::getTable() . '.itemtype' => \State::class,
                DropdownVisibility::getTable() . '.visible_itemtype' => static::class,
                DropdownVisibility::getTable() . '.is_visible' => 1,
            ],
        ];
    }
}
