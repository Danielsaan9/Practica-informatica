<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Remove unexpected values for `pdffont` config and user preference
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_configs',
        [
            'value' => 'dejavusans',
        ],
        [
            'name'  => 'pdffont',
            [
                'value' => ['REGEXP', '[^a-z0-9]+'],
            ],
        ]
    )
);
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_users',
        [
            'pdffont' => null,
        ],
        [
            [
                ['pdffont' => ['REGEXP', '[^a-z0-9]+']],
                ['NOT' => ['pdffont' => null]],
            ],
        ]
    )
);
