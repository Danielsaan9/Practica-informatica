<?php

namespace Glpi\Dashboard\Filters;

use DbUtils;
use Entity;
use Session;

class EntityFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return Entity::getTypeName(Session::getPluralNumber());
    }

    public static function getId(): string
    {
        return "entity";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'entities_id');
    }

    /** @return array<string, mixed> */
    public static function getCriteria(string $table, $value): array
    {
        $criteria = [];

        if ((int) $value > 0) {
            $sons = array_intersect(
                getSonsOf(Entity::getTable(), (int) $value),
                Session::getActiveEntities()
            );

            $itemtype = getItemForTable($table);
            // Use the DbUtils method directly: the global getEntitiesRestrictCriteria()
            // wraps its result under a crc32 key so it merges safely via
            // array_merge_recursive(), which we don't need for a plain assignment here.
            $criteria['WHERE'] = (new DbUtils())->getEntitiesRestrictCriteria(
                $table,
                value: $sons,
                is_recursive: $itemtype?->maybeRecursive() ?? false
            );
        }

        return $criteria;
    }

    /** @return array<int, array<string, mixed>> */
    public static function getSearchCriteria(string $table, $value): array
    {
        $criteria = [];

        $sons = (int) $value > 0
            ? array_intersect(getSonsOf(Entity::getTable(), (int) $value), Session::getActiveEntities())
            : [];

        if ($sons !== []) {
            $criteria[] = [
                'link'       => 'AND',
                'field'      => self::getSearchOptionID($table, 'entities_id', 'glpi_entities'),
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
            'entity',
            Entity::class,
            ['entity' => Session::getActiveEntities()]
        );
    }
}
