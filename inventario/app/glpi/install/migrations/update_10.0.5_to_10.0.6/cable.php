<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$table = 'glpi_cables';
if (!$DB->fieldExists($table, 'is_deleted', false)) {
    $migration->addField($table, 'is_deleted', 'bool');
    $migration->addKey($table, 'is_deleted');
}

$migration->replaceRight('cable_management', READ | UPDATE | CREATE | DELETE | PURGE, [
    'cable_management' => READ | UPDATE | CREATE | PURGE,
]);
