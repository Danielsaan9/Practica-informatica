<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if ($DB->tableExists('glpi_computervirtualmachines')) {
    $migration->renameTable('glpi_computervirtualmachines', 'glpi_itemvirtualmachines');
}

if (!$DB->fieldExists('glpi_itemvirtualmachines', 'itemtype')) {
    $migration->addField(
        'glpi_itemvirtualmachines',
        'itemtype',
        'string',
        [
            'after'  => 'id',
            'update' => $DB->quoteValue('Computer'), // Defines value for all existing elements
        ]
    );
    $migration->migrationOneTable('glpi_itemvirtualmachines');
}

if (!$DB->fieldExists('glpi_itemvirtualmachines', 'items_id')) {
    $migration->dropKey('glpi_itemvirtualmachines', 'computers_id');
    $migration->changeField(
        'glpi_itemvirtualmachines',
        'computers_id',
        'items_id',
        "int {$default_key_sign} NOT NULL DEFAULT '0'",
        [
            'after' => 'itemtype',
        ]
    );
    $migration->migrationOneTable('glpi_itemvirtualmachines');
}

$migration->addKey('glpi_itemvirtualmachines', ['itemtype', 'items_id'], 'item');
