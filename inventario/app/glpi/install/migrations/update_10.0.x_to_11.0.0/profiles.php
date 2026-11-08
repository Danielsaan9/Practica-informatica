<?php

/**
 * @var Migration $migration
 */
$migration->addField(
    'glpi_profiles',
    'last_rights_update',
    'timestamp',
    [
        'null' => false,
        'value' => null,
    ]
);
$migration->addKey('glpi_profiles', 'last_rights_update');

$migration->addField(
    'glpi_profiles',
    'use_mentions',
    'int',
    [
        'null' => false,
        'value' => '1',
        'after' => 'helpdesk_item_type',
    ]
);
