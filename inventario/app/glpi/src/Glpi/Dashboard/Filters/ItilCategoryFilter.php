<?php

namespace Glpi\Dashboard\Filters;

use ITILCategory;
use Session;

class ItilCategoryFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return ITILCategory::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "itilcategory";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'itilcategories_id');
    }

    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $criteria["WHERE"] = [
                "$table.itilcategories_id" => getSonsOf(ITILCategory::getTable(), (int) $value),
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
                'field'      => self::getSearchOptionID($table, 'itilcategories_id', 'glpi_itilcategories'),
                'searchtype' => 'under',
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
            'itilcategory',
            ITILCategory::class
        );
    }
}
