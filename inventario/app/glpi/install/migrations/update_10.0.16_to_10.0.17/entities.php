<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Fix default value for `custom_css_code`
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_entities',
        [
            'custom_css_code' => '',
        ],
        [
            'custom_css_code' => null,
        ]
    )
);
