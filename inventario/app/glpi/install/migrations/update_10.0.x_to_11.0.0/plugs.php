<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if ($DB->tableExists('glpi_pdus_plugs')) {
    $migration->renameTable('glpi_pdus_plugs', 'glpi_items_plugs');
}

if (!$DB->fieldExists('glpi_items_plugs', 'itemtype')) {
    $migration->addField(
        'glpi_items_plugs',
        'itemtype',
        'varchar(255) NOT NULL',
        [
            'after'  => 'plugs_id',
            'update' => $DB::quoteValue('PDU'), // Defines value for all existing elements
        ]
    );
    $migration->migrationOneTable('glpi_items_plugs');
}

if (!$DB->fieldExists('glpi_items_plugs', 'items_id')) {
    $migration->dropKey('glpi_items_plugs', 'pdus_id');
    $migration->changeField(
        'glpi_items_plugs',
        'pdus_id',
        'items_id',
        "int {$default_key_sign} NOT NULL DEFAULT '0'",
        [
            'after' => 'itemtype',
        ]
    );
    $migration->migrationOneTable('glpi_items_plugs');
}

$migration->addKey('glpi_items_plugs', ['itemtype', 'items_id'], 'item');
