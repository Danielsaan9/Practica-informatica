<?php

namespace Glpi\Security;

use Profile;
use Profile_User;

/**
 * Check permission information for a user, including users other than the currently logged in one.
 */
final class PermissionManager
{
    public static function getInstance(): self
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }

    public function getAllEntities(int $users_id): array
    {
        global $DB;

        $profile_table = Profile::getTable();
        $iterator = $DB->request([
            'SELECT' => ['entities_id', 'is_recursive'],
            'FROM' => Profile_User::getTable(),
            'LEFT JOIN' => [
                $profile_table => [
                    'ON'    => [
                        $profile_table => 'id',
                        Profile_User::getTable() => 'profiles_id',
                    ],
                ],
            ],
            'WHERE' => [
                Profile_User::getTableField('users_id') => $users_id,
            ],
        ]);
        $entities = [];
        foreach ($iterator as $row) {
            $entities[] = [$row['entities_id']];
            if ($row['is_recursive']) {
                $entities[] = getSonsOf('glpi_entities', $row['entities_id']);
            }
        }

        // Avoid running array_merge in a loop by storing multiple arrays into $entities
        $entities = array_merge(...$entities);

        return array_unique($entities);
    }
}
