<?php

namespace Glpi\Dashboard\Filters;

use Change;
use Glpi\Features\AssignableItem;
use Group;
use Group_Item;
use Problem;
use Ticket;
use Toolbox;
use UnexpectedValueException;

abstract class AbstractGroupFilter extends AbstractFilter
{
    /**
     * Get the group type
     * @return int
     * @phpstan-return Group_Item::GROUP_TYPE_*
     */
    abstract protected static function getGroupType(): int;

    /**
     * Get the field name for the group when stored directly in the item's table.
     * @return string
     */
    abstract protected static function getGroupFieldName(): string;

    /**
     * Get the search option ID for the group field for Tickets, Changes and Problems.
     * @return int
     */
    abstract protected static function getITILSearchOptionID(): int;

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return Toolbox::hasTrait(getItemtypeForTable($table), AssignableItem::class)
            || $DB->fieldExists($table, 'groups_id')
            || in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true);
    }

    /**
     * @param mixed $value
     * @return int[]
     */
    public static function resolveGroupIds($value): array
    {
        $values = is_array($value) ? $value : [$value];
        // Expand "mygroups" to the current user's groups and normalize to unique positive IDs
        $expanded = [];
        foreach ($values as $v) {
            if ($v === 'mygroups') {
                foreach ($_SESSION['glpigroups'] as $g) {
                    $expanded[] = (int) $g;
                }
            } else {
                $expanded[] = $v;
            }
        }

        return self::normalizeIntValues($expanded);
    }

    public static function getCriteria(string $table, $value): array
    {
        global $DB;

        $criteria  = [];
        $groups_ids = self::resolveGroupIds($value);

        if (count($groups_ids) === 0) {
            return $criteria;
        }

        $groups_ids_value = count($groups_ids) === 1 ? $groups_ids[0] : $groups_ids;

        if ($DB->fieldExists($table, static::getGroupFieldName())) {
            $criteria["WHERE"] = [
                $table . '.' . static::getGroupFieldName() => $groups_ids_value,
            ];
        } elseif (in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true)) {
            $main_item = match ($table) {
                Ticket::getTable() => new Ticket(),
                Change::getTable() => new Change(),
                Problem::getTable() => new Problem(),
                default => throw new UnexpectedValueException(),
            };
            $grouplink = $main_item->grouplinkclass;
            $gl_table  = $grouplink::getTable();
            $fk        = $main_item::getForeignKeyField();
            $alias = self::uniqueAlias('gl');

            $criteria["JOIN"] = [
                "$gl_table as $alias" => [
                    'ON' => [
                        $alias => $fk,
                        $table => 'id',
                    ],
                ],
            ];
            $criteria["WHERE"] = [
                "$alias.type"      => static::getGroupType(),
                "$alias.groups_id" => $groups_ids_value,
            ];
        } else {
            $group_item_table = Group_Item::getTable();
            $alias            = self::uniqueAlias('gi');
            $criteria['JOIN'] = [
                "$group_item_table as $alias" => [
                    'ON' => [
                        $alias => 'items_id',
                        $table => 'id', [
                            'AND' => [
                                "$alias.itemtype" => getItemtypeForTable($table),
                            ],
                        ],
                    ],
                ],
            ];
            $criteria["WHERE"] = [
                "$alias.type"      => static::getGroupType(),
                "$alias.groups_id" => $groups_ids_value,
            ];
        }

        return $criteria;
    }

    public static function getSearchCriteria(string $table, $value): array
    {
        $criteria = [];

        $values = is_array($value) ? $value : [$value];
        $resolved = [];
        foreach ($values as $v) {
            if ($v === 'mygroups') {
                $resolved[] = 'mygroups';
            } elseif ((int) $v > 0) {
                $resolved[] = (int) $v;
            }
        }
        $resolved = array_values(array_unique($resolved));

        if (count($resolved) === 0) {
            return $criteria;
        }

        $build = function (int|string $groups_id) use ($table): array {
            if (in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true)) {
                return [
                    'field'      => static::getITILSearchOptionID(),
                    'searchtype' => 'equals',
                    'value'      => $groups_id,
                ];
            }
            return [
                'field'      => self::getSearchOptionID($table, static::getGroupFieldName(), 'glpi_groups'),
                'searchtype' => 'equals',
                'value'      => $groups_id,
            ];
        };

        if (count($resolved) === 1) {
            $criteria[] = ['link' => 'AND'] + $build($resolved[0]);
        } else {
            $sub = [];
            foreach ($resolved as $i => $groups_id) {
                $sub[] = ['link' => $i === 0 ? 'AND' : 'OR'] + $build($groups_id);
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
        $values = is_array($value) ? array_values($value) : ($value !== null && $value !== '' ? [$value] : []);
        return self::displayMultipleList(
            static::getName(),
            $values,
            static::getId(),
            Group::class,
            [
                'toadd' => ['mygroups' => __("My groups")],
            ]
        );
    }
}
