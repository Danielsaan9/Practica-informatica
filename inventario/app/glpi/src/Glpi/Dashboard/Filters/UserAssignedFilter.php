<?php

namespace Glpi\Dashboard\Filters;

use Change;
use Problem;
use Session;
use Ticket;
use User;

class UserAssignedFilter extends AbstractFilter
{
    use AssignedITILUserFilterTrait;

    public static function getName(): string
    {
        return __("User / Assigned user");
    }

    public static function getId(): string
    {
        return "user_assigned";
    }

    public static function canBeApplied(string $table): bool
    {
        global $DB;

        return $DB->fieldExists($table, 'users_id')
            || in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function getCriteria(string $table, $value): array
    {
        global $DB;

        $criteria = [];

        $users_id = null;
        if ((int) $value > 0) {
            $users_id = (int) $value;
        } elseif ($value === 'myself') {
            $users_id = $_SESSION['glpiID'];
        }

        if ($users_id !== null) {
            if ($DB->fieldExists($table, 'users_id')) {
                $criteria["WHERE"] = [
                    "$table.users_id" => $users_id,
                ];
            } elseif (in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true)) {
                $criteria = self::getAssignedITILUserCriteria($table, $users_id);
            }
        }

        return $criteria;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function getSearchCriteria(string $table, $value): array
    {
        global $DB;

        $criteria = [];

        if ((int) $value > 0 || $value === 'myself') {
            if ($DB->fieldExists($table, 'users_id')) {
                $criteria[] = [
                    'link'       => 'AND',
                    'field'      => self::getSearchOptionID($table, 'users_id', 'glpi_users'),
                    'searchtype' => 'equals',
                    'value'      => $value === 'myself' ? (int) Session::getLoginUserID() : (int) $value,
                ];
            } elseif (in_array($table, [Ticket::getTable(), Change::getTable(), Problem::getTable()], true)) {
                $criteria[] = self::getAssignedITILUserSearchCriteria($value);
            }
        }

        return $criteria;
    }

    public static function getHtml($value): string
    {
        return self::displayList(
            self::getName(),
            is_string($value) ? $value : "",
            self::getId(),
            User::class,
            [
                'right' => 'own_ticket',
                'toadd' => [
                    [
                        'id'   => 'myself',
                        'text' => __('Myself'),
                    ],
                ],
            ]
        );
    }
}
