<?php

namespace Glpi\Dashboard\Filters;

use Session;
use SLA;

class SlaFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return SLA::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "sla";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'slas_id_ttr')
            || $DB->fieldExists($table, 'slas_id_tto');
    }

    /**
     * @return array<string, mixed>
     */
    public static function getCriteria(string $table, $value): array
    {
        global $DB;

        $criteria = [];

        if ((int) $value > 0) {
            $or = [];
            if ($DB->fieldExists($table, 'slas_id_ttr')) {
                $or["$table.slas_id_ttr"] = (int) $value;
            }
            if ($DB->fieldExists($table, 'slas_id_tto')) {
                $or["$table.slas_id_tto"] = (int) $value;
            }

            if ($or !== []) {
                $criteria["WHERE"] = ['OR' => $or];
            }
        }

        return $criteria;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function getSearchCriteria(string $table, $value): array
    {
        global $DB;

        $criteria = [];

        if ((int) $value > 0) {
            if ($DB->fieldExists($table, 'slas_id_ttr')) {
                $criteria[] = [
                    'link'       => count($criteria) ? 'OR' : 'AND',
                    'field'      => self::getSearchOptionID($table, 'slas_id_ttr', 'glpi_slas'),
                    'searchtype' => 'equals',
                    'value'      => (int) $value,
                ];
            }
            if ($DB->fieldExists($table, 'slas_id_tto')) {
                $criteria[] = [
                    'link'       => count($criteria) ? 'OR' : 'AND',
                    'field'      => self::getSearchOptionID($table, 'slas_id_tto', 'glpi_slas'),
                    'searchtype' => 'equals',
                    'value'      => (int) $value,
                ];
            }
        }

        return $criteria;
    }

    public static function getHtml($value): string
    {
        return self::displayList(
            self::getName(),
            is_string($value) ? $value : "",
            'sla',
            SLA::class
        );
    }
}
