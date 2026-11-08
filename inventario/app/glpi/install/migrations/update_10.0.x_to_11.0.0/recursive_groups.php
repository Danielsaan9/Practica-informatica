<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Add new "recursive_membership" field on groups
// If enabled, members of a given group will become implicits members of its
// children groups
// Disabled by default
if (!$DB->fieldExists('glpi_groups', 'recursive_membership')) {
    $migration->addField('glpi_groups', 'recursive_membership', 'bool', [
        'value' => 0,
    ]);
}
