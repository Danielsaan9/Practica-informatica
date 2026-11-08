<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if ($DB->tableExists('glpi_computerantiviruses')) {
    $migration->renameTable('glpi_computerantiviruses', 'glpi_itemantiviruses');
}

if (!$DB->fieldExists('glpi_itemantiviruses', 'itemtype')) {
    $migration->addField(
        'glpi_itemantiviruses',
        'itemtype',
        'string',
        [
            'after'  => 'id',
            'update' => $DB->quoteValue('Computer'), // Defines value for all existing elements
        ]
    );
    $migration->migrationOneTable('glpi_itemantiviruses');
}

if (!$DB->fieldExists('glpi_itemantiviruses', 'items_id')) {
    $migration->dropKey('glpi_itemantiviruses', 'computers_id');
    $migration->changeField(
        'glpi_itemantiviruses',
        'computers_id',
        'items_id',
        "int {$default_key_sign} NOT NULL DEFAULT '0'",
        [
            'after' => 'itemtype',
        ]
    );
    $migration->migrationOneTable('glpi_itemantiviruses');
}

$migration->addKey('glpi_itemantiviruses', ['itemtype', 'items_id'], 'item');
