<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$table = 'glpi_pendingreasons';
// Add new "is_default" field on pendingreasons table
if (!$DB->fieldExists($table, 'is_default')) {
    $migration->addField($table, 'is_default', 'bool', ['value' => 0]);
}

// Add new "is_pending_per_default" field on pendingreasons table
if (!$DB->fieldExists($table, 'is_pending_per_default')) {
    $migration->addField($table, 'is_pending_per_default', 'bool', ['value' => 0]);
}

// Add new "calendars_id" field on pendingreasons table
$fkey_to_add = 'calendars_id';
if (!$DB->fieldExists($table, $fkey_to_add)) {
    $migration->addField($table, $fkey_to_add, 'fkey');
    $migration->addKey($table, $fkey_to_add);
}
