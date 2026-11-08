<?php

namespace Glpi\Dashboard\Filters;

use Manufacturer;
use Session;

class ManufacturerFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return Manufacturer::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "manufacturer";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'manufacturers_id');
    }

    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria["WHERE"] = [
                "$table.manufacturers_id" => (int) $value,
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
                'field'      => self::getSearchOptionID($table, 'manufacturers_id', 'glpi_manufacturers'),
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
            'manufacturer',
            Manufacturer::class
        );
    }
}
