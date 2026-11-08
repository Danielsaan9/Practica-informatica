<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

if ($DB->tableExists('glpi_computers_items')) {
    $migration->renameTable('glpi_computers_items', 'glpi_assets_assets_peripheralassets');
}

// Main item polymorphic foreign key
$migration->dropKey('glpi_assets_assets_peripheralassets', 'computers_id');
$migration->addField(
    'glpi_assets_assets_peripheralassets',
    'itemtype_asset',
    'varchar(255) NOT NULL',
    [
        'after'  => 'id',
        'update' => $DB->quoteValue('Computer'), // Defines value for all existing elements
    ]
);
if ($DB->fieldExists('glpi_assets_assets_peripheralassets', 'computers_id')) {
    $migration->changeField(
        'glpi_assets_assets_peripheralassets',
        'computers_id',
        'items_id_asset',
        'fkey'
    );
}
$migration->migrationOneTable('glpi_assets_assets_peripheralassets');
$migration->addKey('glpi_assets_assets_peripheralassets', ['itemtype_asset', 'items_id_asset'], 'item_asset');

// Peripheral polymorphic foreign key
$migration->dropKey('glpi_assets_assets_peripheralassets', 'item');
if ($DB->fieldExists('glpi_assets_assets_peripheralassets', 'itemtype')) {
    $migration->changeField(
        'glpi_assets_assets_peripheralassets',
        'itemtype',
        'itemtype_peripheral',
        'varchar(255) NOT NULL'
    );
}
if ($DB->fieldExists('glpi_assets_assets_peripheralassets', 'items_id')) {
    $migration->changeField(
        'glpi_assets_assets_peripheralassets',
        'items_id',
        'items_id_peripheral',
        'fkey'
    );
}
$migration->migrationOneTable('glpi_assets_assets_peripheralassets');
$migration->addKey('glpi_assets_assets_peripheralassets', ['itemtype_peripheral', 'items_id_peripheral'], 'item_peripheral');
