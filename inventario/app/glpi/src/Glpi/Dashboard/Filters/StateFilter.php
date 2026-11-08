<?php

namespace Glpi\Dashboard\Filters;

use Session;
use State;

class StateFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return State::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "state";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'states_id');
    }

    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];
        $states_ids = self::normalizeIntValues($value);

        if (count($states_ids) === 0) {
            return $criteria;
        }

        $criteria["WHERE"] = [
            "$table.states_id" => count($states_ids) === 1 ? $states_ids[0] : $states_ids,
        ];

        return $criteria;
    }

    public static function getSearchCriteria(string $table, $value): array
    {
        $criteria = [];
        $states_ids = self::normalizeIntValues($value);

        if (count($states_ids) === 0) {
            return $criteria;
        }

        $so_id = self::getSearchOptionID($table, 'states_id', 'glpi_states');

        if (count($states_ids) === 1) {
            $criteria[] = [
                'link'       => 'AND',
                'field'      => $so_id,
                'searchtype' => 'equals',
                'value'      => $states_ids[0],
            ];
        } else {
            $sub = [];
            foreach ($states_ids as $i => $states_id) {
                $sub[] = [
                    'link'       => $i === 0 ? 'AND' : 'OR',
                    'field'      => $so_id,
                    'searchtype' => 'equals',
                    'value'      => $states_id,
                ];
            }
            $criteria[] = [
                'link'     => 'AND',
                'criteria' => $sub,
            ];
        }

        return $criteria;
    }

    public static function getHtml($value): string
    {
        return self::displayMultipleList(
            self::getName(),
            self::normalizeIntValues($value),
            self::getId(),
            State::class
        );
    }
}
