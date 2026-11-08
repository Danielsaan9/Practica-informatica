<?php

namespace Glpi\Dashboard\Filters;

use RequestType;
use Session;

class RequestTypeFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return RequestType::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "requesttype";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'requesttypes_id');
    }

    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria["WHERE"] = [
                "$table.requesttypes_id" => (int) $value,
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
                'field'      => self::getSearchOptionID($table, 'requesttypes_id', 'glpi_requesttypes'),
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
            'requesttype',
            RequestType::class
        );
    }
}
