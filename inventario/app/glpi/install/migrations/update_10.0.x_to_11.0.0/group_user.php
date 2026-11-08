<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists('glpi_groups_users', 'is_userdelegate')) {
    $migration->addField(
        'glpi_groups_users',
        'is_userdelegate',
        "tinyint NOT NULL DEFAULT '0'",
        ['after' => 'is_manager']
    );
    $migration->addKey(
        'glpi_groups_users',
        'is_userdelegate'
    );
}
