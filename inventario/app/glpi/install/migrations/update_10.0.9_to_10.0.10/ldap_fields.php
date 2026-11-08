<?php

use function Safe\preg_match;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

/** Fix non encoded LDAP fields in groups */
$groups = getAllDataFromTable('glpi_groups');
foreach ($groups as $group) {
    $updated = [];
    foreach (['ldap_group_dn', 'ldap_value'] as $ldap_field) {
        if ($group[$ldap_field] !== null && preg_match('/(<|>|(&(?!#?[a-z0-9]+;)))/i', $group[$ldap_field]) === 1) {
            $updated[$ldap_field] = $group[$ldap_field];
        }
    }
    if (count($updated) > 0) {
        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_groups',
                $updated,
                [
                    'id' => $group['id'],
                ]
            )
        );
    }
}
/** /Fix non encoded LDAP fields in groups */

/** Fix non encoded LDAP fields in users */
$users = $DB->request([
    'SELECT' => [
        'glpi_users.id',
        'glpi_users.user_dn',
        'glpi_users.sync_field',
    ],
    'FROM'   => 'glpi_users',
    'WHERE'  => [
        'authtype' => 3,
        [
            'OR' => [
                // only a pre-filter, MySQL 5.7 does not support the complex regex used in PHP
                'user_dn' => ['REGEXP', '(<|>|&)'],
                'sync_field' => ['REGEXP', '(<|>|&)'],
            ],
        ],
    ],
]);
foreach ($users as $user) {
    $updated = [];
    foreach (['user_dn', 'sync_field'] as $ldap_field) {
        if ($user[$ldap_field] !== null && preg_match('/(<|>|(&(?!#?[a-z0-9]+;)))/i', $user[$ldap_field]) === 1) {
            $updated[$ldap_field] = $user[$ldap_field];
        }
    }
    if (count($updated) > 0) {
        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_users',
                $updated,
                [
                    'id' => $user['id'],
                ]
            )
        );
    }
}
/** /Fix non encoded LDAP fields in groups */
