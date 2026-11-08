<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Add user_dn_hash field
$migration->addField('glpi_users', 'user_dn_hash', 'varchar(32)', [
    'after'  => 'user_dn',
]);


$users_iterator = $DB->request(
    [
        'SELECT' => ['id', 'user_dn'],
        'FROM'   => 'glpi_users',
        'WHERE'  => [
            'NOT' => [
                'user_dn' => null,
            ],
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

// Add user_dn_hash index
$migration->addKey('glpi_users', 'user_dn_hash');
