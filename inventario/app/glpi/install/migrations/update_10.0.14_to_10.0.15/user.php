<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Fix user_dn_hash related to `user_dn` containing a special char
$users_iterator = $DB->request(
    [
        'SELECT' => ['id', 'user_dn'],
        'FROM'   => 'glpi_users',
        'WHERE'  => [
            // `user_dn` will contains a `&` char if any of its char was sanitized
            'user_dn' => ['LIKE', '%&%'],
        ],
    ]
);
foreach ($users_iterator as $user_data) {
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_users',
            [
                'user_dn_hash' => md5($user_data['user_dn']),
            ],
            [
                'id' => $user_data['id'],
            ]
        )
    );
}
