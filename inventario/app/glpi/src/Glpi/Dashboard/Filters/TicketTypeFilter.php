<?php

namespace Glpi\Dashboard\Filters;

use Session;
use Ticket;

class TicketTypeFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return _n("Ticket type", "Ticket types", Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "tickettype";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'type');
    }

    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria["WHERE"] = [
                "$table.type" => (int) $value,
            ];
        }

        return $criteria;
    }

    public static function getSearchCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria[] = [
                'link'       => 'AND',
                'field'      => self::getSearchOptionID($table, 'type', $table),
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
            'tickettype',
            Ticket::class,
            [
                'condition' => ['id' => -1],
                'toadd'     => Ticket::getTypes(),
            ]
        );
    }
}
