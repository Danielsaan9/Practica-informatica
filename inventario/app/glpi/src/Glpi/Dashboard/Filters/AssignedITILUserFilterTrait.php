<?php

namespace Glpi\Dashboard\Filters;

use Change;
use CommonITILActor;
use Problem;
use Ticket;
use UnexpectedValueException;

/**
 * Shared logic to filter ITIL items (Ticket, Change, Problem) by their
 * assigned technician (user actor).
 */
trait AssignedITILUserFilterTrait
{
    /**
     * Build the JOIN/WHERE criteria matching an ITIL item assigned to a given user.
     *
     * @return array<string, array<string, mixed>>
     */
    protected static function getAssignedITILUserCriteria(string $table, int $users_id, ?string $alias = null): array
    {
        $alias ??= self::uniqueAlias('ul');

        $main_item = match ($table) {
            Ticket::getTable()  => new Ticket(),
            Change::getTable()  => new Change(),
            Problem::getTable() => new Problem(),
            default => throw new UnexpectedValueException("Table $table is not a supported ITIL table"),
        };
        $userlink = $main_item->userlinkclass;
        $ul_table = $userlink::getTable();
        $fk       = $main_item->getForeignKeyField();

        return [
            'JOIN' => [
                "$ul_table as $alias" => [
                    'ON' => [
                        $alias  => $fk,
                        $table  => 'id',
                    ],
                ],
            ],
            'WHERE' => [
                "$alias.type"     => CommonITILActor::ASSIGN,
                "$alias.users_id" => $users_id,
            ],
        ];
    }

    /**
     * Build the search criterion matching an ITIL item assigned to a given user.
     *
     * @param mixed $value
     *
     * @return array<string, mixed>
     */
    protected static function getAssignedITILUserSearchCriteria($value): array
    {
        return [
            'link'       => 'AND',
            'field'      => 5, // assigned technician
            'searchtype' => 'equals',
            'value'      => is_numeric($value) ? (int) $value : $value,
        ];
    }
}
