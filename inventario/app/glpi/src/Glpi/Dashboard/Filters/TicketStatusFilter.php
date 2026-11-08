<?php

namespace Glpi\Dashboard\Filters;

use Session;
use Ticket;

class TicketStatusFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return _n("Ticket status", "Ticket statuses", Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "ticketstatus";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $table === Ticket::getTable() && $DB->fieldExists($table, 'status');
    }

    /**
     * @return array<string, array<string, int>>
     */
    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria["WHERE"] = [
                "$table.status" => (int) $value,
            ];
        }

        return $criteria;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function getSearchCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria[] = [
                'link'       => 'AND',
                'field'      => self::getSearchOptionID($table, 'status', $table),
                'searchtype' => 'equals',
                'value'      => (int) $value,
            ];
        }

        return $criteria;
    }

    public static function getHtml($value): string
    {
        return self::displayList(
            self::getName(),
            is_string($value) ? $value : "",
            'ticketstatus',
            Ticket::class,
            ['value' => 'all'],
            'dropdownStatus',
        );
    }
}
