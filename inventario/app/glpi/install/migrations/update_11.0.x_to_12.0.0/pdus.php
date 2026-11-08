<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */

$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();


$migration->addField(
    'glpi_pdus',
    'sysdescr',
    'text',
    ['after' => 'users_id_tech']
);

$migration->addField(
    'glpi_pdus',
    'last_inventory_update',
    'timestamp',
    ['after' => 'sysdescr']
);

$migration->addField(
    'glpi_pdus',
    'snmpcredentials_id',
    "int {$default_key_sign} NOT NULL DEFAULT '0'",
    ['after' => 'last_inventory_update']
);

$migration->addField(
    'glpi_pdus',
    'autoupdatesystems_id',
    "int {$default_key_sign} NOT NULL DEFAULT '0'",
    ['after' => 'snmpcredentials_id']
);

$migration->addField(
    'glpi_pdus',
    'is_dynamic',
    'bool',
    ['after' => 'autoupdatesystems_id']
);

$migration->addKey('glpi_pdus', 'snmpcredentials_id');
$migration->addKey('glpi_pdus', 'autoupdatesystems_id');
$migration->addKey('glpi_pdus', 'is_dynamic');
$migration->migrationOneTable('glpi_pdus');
